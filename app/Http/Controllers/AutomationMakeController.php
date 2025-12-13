<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AutomationMake;
use Illuminate\Support\Facades\Http;

class AutomationMakeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('make.index');
    }

    public function sendToMake(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png'
        ]);

        $path = $request->file('document')->store('uploads', 'public');

        $fileUrl = asset('storage/' . $path);

        $response = Http::post('https://hook.eu1.make.com/msbfu7x7a5yvfwz1zqlrlb8dcnoxqymv', [
            'file_url' => $fileUrl,
            'file_type' => $request->file('document')->getClientMimeType(),
        ]);

        return response()->json($response->json([$fileUrl]));
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
    public function show(AutomationMake $automationMake)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AutomationMake $automationMake)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AutomationMake $automationMake)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AutomationMake $automationMake)
    {
        //
    }
}
