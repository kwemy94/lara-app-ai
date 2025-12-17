<?php

namespace App\Http\Controllers;

use App\Models\OpenAIAPI;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('open-ai.index');
    }

    public function sendBL(Request $request)
    {
        // $path = $request->file('document')->store('temp');

        // $imageUrl = asset('storage/' . $path);

        $file = $request->file('document');

                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                // chemin réel public
                $file->move(
                    public_path('storage/bl_documents'),
                    $filename
                );

                $imageUrl = asset('storage/bl_documents/' . $filename);
                // dd($imageUrl);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Tu es un assistant OCR. Réponds uniquement en JSON valide.'
                ],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Analyse ce document et retourne uniquement ce JSON :
                        {
                          "port_embarquement": "",
                          "port_destination": "",
                          "port_de_decharge": ""
                        }'
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $imageUrl
                            ]
                        ]
                    ]
                ]
            ],
            'temperature' => 0,
        ]);

        return response()->json(
            json_decode($response->choices[0]->message->content, true)
        );
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
    public function show(OpenAIAPI $openAIAPI)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OpenAIAPI $openAIAPI)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OpenAIAPI $openAIAPI)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OpenAIAPI $openAIAPI)
    {
        //
    }
}
