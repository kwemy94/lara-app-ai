<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BLController extends Controller
{
    public function handleUpload(Request $request)
    {
        $file = $request->file('document');
        $path = $file->store('uploads');
        $absolutePath = storage_path('app/private/' . $path);

        // 1. OCR du document
        $text = $this->extractTextWithOCR($absolutePath);
        // dd($text);
        // 2. Extraction par LLM
        $data = $this->extractDataWithLLM($text);

        // 3. Retour au formulaire D
        return view('form-d', [
            'text' => $text,
            'destinationCountry' => $data['destination_country'] ?? null,
            'port' => $data['port_of_discharge'] ?? null,
            'shipper' => $data['shipper'] ?? null,
            'consignee' => $data['consignee'] ?? null,
        ]);
    }

    private function extractTextWithOCR($filePath)
    {
        $response = Http::asMultipart()->post(
            config('services.ocrspace.endpoint'),
            [
                'apikey' => config('services.ocrspace.key'),
                'language' => 'eng',
                'file' => fopen($filePath, 'r'),
            ]
        );

        if ($response->failed()) {
            throw new \Exception("Impossible d'appeler l'API OCR : " . $response->body());
        }

        return $response->json()['ParsedResults'][0]['ParsedText'] ?? '';
    }

    private function extractDataWithLLM($text)
    {
        $prompt = "
            Voici un BL. Extrait et retourne en JSON :
            - port_of_discharge
            - destination_country
            - shipper
            - consignee

            Document :
            $text
        ";

        $response = Http::withToken(config('services.openai.key'))
            ->post("https://api.openai.com/v1/responses", [
                "model" => config('services.openai.model'),
                "input" => $prompt,
                "text" => [
                    "format" => "json"
                ]
            ]);

        // gestion d'erreur API
        if (!$response->successful()) {
            throw new \Exception("Erreur API OpenAI : " . $response->body());
        }

        $json = $response->json();

        if (!isset($json['output'][0]['content'][0]['text'])) {
            throw new \Exception("Réponse OpenAI invalide : " . json_encode($json));
        }

        return json_decode($json['output'][0]['content'][0]['text'], true);
    }
}

