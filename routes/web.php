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