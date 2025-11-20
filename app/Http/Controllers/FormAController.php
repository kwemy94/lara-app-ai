<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

class FormAController extends Controller
{
    public function submit(Request $request)
    {
        // 1. Validation
        $request->validate([
            'documents' => 'required|array|min:3|max:3',
            'documents.*' => 'required|file|mimes:pdf,txt,jpeg,png,jpg,webp,docx'
        ]);

        $texts = [];

        // 2. Traitement de chaque fichier
        foreach ($request->file('documents') as $file) {

            // Sauvegarde temporaire
            $path = $file->store('uploads');
            

            $extension = $file->getClientOriginalExtension();

            // 3. OCR selon le type
            if (in_array($extension, ['jpeg', 'jpg', 'png', 'webp'])) {
                // OCR direct sur image
                // $text = (new TesseractOCR(storage_path('app/' . $path)))
                //     ->lang('fra', 'eng')
                //     ->run();
                // $imgPath = $this->normalizePath(storage_path('app/' . $path));
                $imgPath = $this->normalizePath(storage_path('app/private/' . $path));

                // dd(env('TESSERACT_PATH'));
                $text = (new TesseractOCR($imgPath))
                    ->executable(env('TESSERACT_PATH'))
                    ->lang('fra', 'eng')
                    ->run();


            } elseif ($extension === 'pdf') {
                // Convert PDF → image(s)
                $images = $this->pdfToImages(storage_path('app/' . $path));
                $text = "";

                foreach ($images as $imgPath) {
                    $text .= (new TesseractOCR($imgPath))->run() . "\n";
                }

            } elseif ($extension === 'txt') {
                $text = file_get_contents(storage_path('app/' . $path));

            } elseif ($extension === 'docx') {
                $text = $this->extractDocxText(storage_path('app/' . $path));
            }

            $texts[] = $text;
        }

        // 4. Fusion des textes
        $fullText = implode("\n\n", $texts);

        // 5. Extraction d'information (LLM ou regex)
        $extracted = $this->extractInfo($fullText);

        // 6. Affichage du formulaire B pré-rempli
        return view('formB', ['data' => $extracted]);
    }


    private function pdfToImages($pdfPath)
    {
        $imagesPath = [];
        $output = storage_path('app/pdf_images/' . Str::random(10));

        // // utilisera ImageMagick
        // exec("magick convert -density 300 {$pdfPath} -quality 90 {$output}.png");

        $pdfPath = $this->normalizePath($pdfPath);
        $output = $this->normalizePath($output);

        exec("magick convert -density 300 \"$pdfPath\" -quality 90 \"{$output}.png\"");


        foreach (glob($output . '*.png') as $img) {
            $imagesPath[] = $img;
        }

        return $imagesPath;
    }

    private function extractDocxText($path)
    {
        $zip = zip_open($path);
        $content = "";

        if (!$zip)
            return "";

        while ($zip_entry = zip_read($zip)) {
            if (zip_entry_name($zip_entry) === "word/document.xml") {
                zip_entry_open($zip, $zip_entry);
                $content = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                zip_entry_close($zip_entry);
            }
        }

        zip_close($zip);

        return strip_tags($content);
    }

    private function extractInfo($text)
    {
        // Version simple via REGEX
        return [
            'nom' => $this->extractRegex('/Nom\s*:\s*(.+)/i', $text),
            'numero' => $this->extractRegex('/Num(é|e)ro\s*:\s*([0-9]+)/i', $text),
            'date' => $this->extractRegex('/(\d{2}\/\d{2}\/\d{4})/', $text),
            'adresse' => $this->extractRegex('/Adresse\s*:\s*(.+)/i', $text),
        ];
    }

    private function extractRegex($regex, $text)
    {
        preg_match($regex, $text, $matches);
        return $matches[1] ?? null;
    }

    private function normalizePath($path)
    {
        return str_replace('\\', '/', $path);
    }

}
