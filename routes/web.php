<?php

use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\ThemeController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', HandlePrecognitiveRequests::class])->group(function () {
    Route::resources([
        'participants' => ParticipantController::class,
        'scores' => ScoreController::class,
        'themes' => ThemeController::class,
    ]);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
