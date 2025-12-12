<?php

namespace App\Http\Controllers;

use App\Models\DocumentAI;
use App\Models\N8n;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class N8nController extends Controller
{

    public function sendDocumentToN8N(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png'
        ]);

        $filePath = $request->file('document')->getRealPath();
        $fileName = $request->file('document')->getClientOriginalName();

        // Prépare le fichier en multipart
        $response = Http::attach(
            'file',
            file_get_contents($filePath),
            $fileName
        )->post('https://shell-test.app.n8n.cloud/webhook-test/5e53a734-e4e9-43c7-8746-057435116a9f');
        // https://hook.eu1.make.com/zioyhr3uwt1u64cd7bvdp97asbq1jpma

        if ($response->failed()) {
            return response()->json(['status' => 'error', 'message' => 'Erreur de communication n8n'], 500);
        }

        $data = $response->json(); // ports extraits par n8n

        // Sauvegarde dans la base
        N8n::create([
            'port_depart' => $data['port_depart'] ?? null,
            'port_destination' => $data['port_destination'] ?? null,
        ]);

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function sendToMake(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:pdf,jpg,jpeg,png',
        ]);

        $file = $request->file('document');

        $response = Http::attach(
            'file',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post('https://hook.eu1.make.com/zioyhr3uwt1u64cd7bvdp97asbq1jpma', [
                    'document_id' => 123, // ou autre info utile
                ]);

        if ($response->successful()) {
            return back()->with('success', 'Données envoyées et analysées.');
        }

        return back()->with('error', 'Erreur lors de l’analyse.');
    }


    public function receiveMakeResult(Request $request)
    {
        $data = $request->validate([
            'document_id' => 'required',
            'port_embarquement' => 'required|string',
            'port_destination' => 'required|string',
        ]);

        DocumentAI::where('id', $data['document_id'])->update([
            'port_embarquement' => $data['port_embarquement'],
            'port_destination' => $data['port_destination'],
        ]);

        return response()->json(['message' => 'OK']);
    }




    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('n8n.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(N8n $n8n)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(N8n $n8n)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, N8n $n8n)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(N8n $n8n)
    {
        //
    }
}
