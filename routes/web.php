<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\LanguageController;

Route::get('/', function () {
    return view('welcome');
});

// Character view route
Route::get('/characters', function () {
    return view('characters');
})->name('characters.view');

// Character API routes
Route::prefix('characters')->group(function () {
    Route::get('/fetch', [CharacterController::class, 'fetch'])->name('characters.fetch');
    Route::get('/api', [CharacterController::class, 'index'])->name('characters.api');
    Route::get('/{id}', [CharacterController::class, 'show'])->name('characters.show');
});

// Language routes
Route::post('/language/change', [LanguageController::class, 'changeLanguage'])->name('language.change');
Route::get('/language/translations', [LanguageController::class, 'getTranslations'])->name('language.translations');
