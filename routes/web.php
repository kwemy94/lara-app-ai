<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BLController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/formA-submit', [App\Http\Controllers\FormAController::class, 'submit'])
    ->name('formA.submit');
Route::post('/form-c/upload', [BLController::class, 'handleUpload']);
Route::get('/form-d', function () {
    return view('form-d');
})->name('form-d');


