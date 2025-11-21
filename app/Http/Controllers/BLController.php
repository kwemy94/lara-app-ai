<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BLController extends Controller
{
    public function handleUpload(Request $request)
    {
        $file = $request->file('document');
        $path = $file->store('uploads');

        $absolutePath = storage_path('app/private/' . $path);

        dd($absolutePath);
        // OCR externe
        $text = $this->extractTextWithOCR($absolutePath);

        // Extraction du pays
        // preg_match('/PORT OF DISCHARGE\s*([A-Z]+)/i', $text, $match);
        // $destinationCountry = $match[1] ?? null;

        $destinationCountry = $this->getDestinationCountry($text);

        return view('form-d', compact('text', 'destinationCountry'));

    }



    // --- Extraction du pays ---
    private function getDestinationCountry($text)
    {
        // Capture du port (tolère plusieurs mots)
        preg_match('/PORT OF DISCHARGE\s*[:\-]*\s*([A-Z\s]+)/i', $text, $match);

        if (!isset($match[1])) {
            return 'UNKNOWN';
        }

        // On prend le premier mot du port
        $port = explode(' ', trim($match[1]))[0];
        $port = strtoupper($port);

        // Mapping port -> pays
        $mapping = [
            'KRIBI' => 'CAMEROON',
            'DOUALA' => 'CAMEROON',
            'COTONOU' => 'BENIN',
            'LOME' => 'TOGO',
            'ABIDJAN' => 'CÔTE D\'IVOIRE',
            'MOMBASA' => 'KENYA',
            'DAR' => 'TANZANIA',           // DAR = Dar es Salaam
            'SALAM' => 'TANZANIA',         // au cas où OCR sépare
        ];

        return $mapping[$port] ?? 'UNKNOWN';
    }




    public function extractTextWithOCR($filePath)
    {
        try {
            $response = Http::asMultipart()->post(
                config('services.ocrspace.endpoint'),
                [
                    'apikey' => config('services.ocrspace.key'),
                    'language' => 'fre',
                    'isOverlayRequired' => false,
                    'file' => fopen($filePath, 'r'),
                ]
            );
        } catch (Exception $e) {
            throw new Exception("Impossible d'appeler l'API OCR : " . $e->getMessage());
        }

        if ($response->failed()) {
            throw new Exception("Erreur OCR API : " . $response->body());
        }

        $json = $response->json();

        return $json['ParsedResults'][0]['ParsedText'] ?? '';
    }


}