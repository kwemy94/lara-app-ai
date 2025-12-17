<?php

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BLController;
use App\Http\Controllers\TaggunController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/formA-submit', [App\Http\Controllers\FormAController::class, 'submit'])
    ->name('formA.submit');
Route::post('/form-c/upload', [BLController::class, 'handleUpload']);
Route::get('/form-d', function () {
    return view('form-d');
})->name('form-d');

Route::get('/taggun', [TaggunController::class, 'index'])->name('taggun.index');
Route::post('/taggun/upload', [TaggunController::class, 'handleUpload'])->name('taggun.upload');
Route::resource('/n8n', App\Http\Controllers\N8nController::class);
Route::post('/n8n/upload', [App\Http\Controllers\N8nController::class, 'sendDocumentToN8N'])->name('n8n.upload');
Route::post('/send-to-make', [App\Http\Controllers\N8nController::class, 'sendToMake'])->name('n8n.sendToMake');
Route::post('/send-to-make-v2', [App\Http\Controllers\AutomationMakeController::class, 'sendToMake'])->name('make.sendToMake');
Route::resource('/make', App\Http\Controllers\AutomationMakeController::class);
Route::post('/openai/send-bl', [App\Http\Controllers\OpenAIAPIController::class, 'sendBL'])->name('openai.sendBL');
Route::resource('/openai', App\Http\Controllers\OpenAIAPIController::class);

Route::get('/ai/test', function () {
    try {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-4-turbo-preview',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful Laravel assistant.'],
                ['role' => 'user', 'content' => 'Explain Laravel in exactly 3 sentences. Be encouraging!'],
            ],
        ]);

        $response = $result->choices[0]->message->content;

        return response()->json([
            'success' => true,
            'response' => $response,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
});