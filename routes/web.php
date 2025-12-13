<?php

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