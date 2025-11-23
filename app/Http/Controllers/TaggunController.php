<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;

class TaggunController extends Controller
{
    public function index()
    {
        return view('taggun.index');
    }

    public function handleUpload(Request $request)
    {
        $file = $request->file('document');
        $path = $file->store('uploads');

        $absolutePath = storage_path('app/private/' . $path);

        // Appel à l'API Taggun
        $response = $this->extractDataWithTaggun($absolutePath);
        dd($this->extractFieldsWithLLM($response['text']['text'] ?? ''));

        return view('form-d', compact('text', 'destinationCountry'));
    }

    public function extractDataWithTaggun($filePath)
    {
        $apiKey = config('services.taggun.key');
        $url = config('services.taggun.endpoint');
        // dd($apiKey, $url);
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post($url, [
                'headers' => [
                    'apikey' => $apiKey,
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($filePath, 'r'),
                    ],
                ],
            ]);

            $body = $response->getBody();
            $data = json_decode($body, true);
            // dd($data, $data['text']);
            return $data;
        } catch (\Exception $e) {
            dd('ERREUR TAGGUN : ' . $e);
            return ['error' => $e->getMessage()];
        }
    }




    private function extractFieldsWithLLM($rawText)
    {
        $prompt = "
Voici un texte OCR issu d'un document maritime (Bill of Lading).
Ton rôle est d'extraire les champs suivants :

- Port of Discharge
- Port of Loading (optionnel)
- Vessel name (optionnel)
- Consignee (optionnel)

Format attendu : JSON valide.

Texte OCR :
--------------------
$rawText
--------------------

Donne uniquement le JSON, pas d’explication.
";

        // Appel des nouveaux modèles (API 2025)
        // $client = OpenAI::client(config('openai.api_key'));
        $response = OpenAI::completions()->create([
            // 'model' => config('openai.model'),
            // 'input' => $prompt
            'model' => 'text-davinci-003',
            'input' => 'PHP is'
        ]);

        // Debug si besoin
        dd($response);

        // $jsonText = $response->output_text ?? '{}';

        // return json_decode($jsonText, true);
    }



}
