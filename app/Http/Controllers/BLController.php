<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;

class BLController extends Controller
{
    public function handleUpload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf,docx'
        ]);

        // 1. Sauvegarder le fichier dans "private/uploads"
        $path = $request->file('document')->store('uploads');

        $fullPath = storage_path("app/private/{$path}");

        // 2. Selon le type de fichier -> extraire le texte
        $ext = strtolower($request->file('document')->getClientOriginalExtension());

        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $text = $this->extractTextFromImage($fullPath);

        } elseif ($ext === 'pdf') {
            $text = $this->extractTextFromPdf($fullPath);

        } elseif ($ext === 'docx') {
            $text = $this->extractTextFromDocx($fullPath);

        } else {
            return back()->with('error', 'Format non supporté');
        }

        // 3. Extraire le pays de destination
        $country = $this->getDestinationCountry($text);
        // dump($country);

        // 4. On envoie à Formulaire D (préremplir)
        // return redirect()->route('form-d')->with([
        //     'destination_country' => $country,
        //     'raw_text' => $text
        // ]);
        return view('form-d', [
            'destination_country' => $country,
            'raw_text' => $text
        ]);
    }

    // --- OCR sur image ---
    private function extractTextFromImage($path)
    {
        return (new TesseractOCR($path))
            ->lang('eng')
            ->run();
    }

    // --- PDF vers texte ---
    private function extractTextFromPdf($path)
    {
        $parser = new PdfParser(); 
        $pdf = $parser->parseFile($path);
        // dd($pdf->getText());
        return $pdf->getText();
    }

    // --- DOCX vers texte ---
    private function extractTextFromDocx($path)
    {
        $phpWord = IOFactory::load($path);
        $text = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . "\n";
                }
            }
        }

        return $text;
    }

    // --- Extraction du pays ---
    private function getDestinationCountry($text)
    {
        // 1. On cherche "Port of Discharge"
        preg_match('/PORT OF DISCHARGE\s*([A-Z]+)/i', $text, $match);
        // dump($match);
        if (!isset($match[1])) {
            return 'UNKNOWN';
        }

        $port = strtoupper(trim($match[1]));

        // 2. Mapping port -> pays
        $mapping = [
            'KRIBI' => 'CAMEROON',
            'DOUALA' => 'CAMEROON',
            'COTONOU' => 'BENIN',
            'LOME' => 'TOGO',
            'ABIDJAN' => 'CÔTE D\'IVOIRE',
            'MOMBASA' => 'KENYA',
            'DAR' => 'TANZANIA',
        ];

        return $mapping[$port] ?? 'UNKNOWN';
    }
}
