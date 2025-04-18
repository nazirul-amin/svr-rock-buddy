<?php

use App\Http\Controllers\CurrentNews;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\SubmissionController;
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

Route::get('/news', CurrentNews::class)->name('news');
Route::get('/rankings/themes/{theme}', [RankingController::class, 'theme'])->name('rankings.theme');
Route::get('/rankings/overall', [RankingController::class, 'overall'])->name('rankings.overall');

Route::middleware(['auth', 'verified', HandlePrecognitiveRequests::class])->group(function () {
    Route::post('participants/bulk', [ParticipantController::class, 'bulkStore'])->name('participants.bulk');
    Route::post('submissions/{submission}/score', [ScoreController::class, 'store'])->name('submissions.score');
    Route::resources([
        'participants' => ParticipantController::class,
        'scores' => ScoreController::class,
        'themes' => ThemeController::class,
        'submissions' => SubmissionController::class,
    ]);
});

Route::middleware([HandlePrecognitiveRequests::class])->group(function () {
    Route::get('themes/{theme}/submit', [SubmissionController::class, 'publicForm'])->name('submissions.public.form');
    Route::post('themes/{theme}/submit', [SubmissionController::class, 'publicStore'])->name('submissions.public.store');
    Route::get('themes/{theme}/submit/thankyou', [SubmissionController::class, 'publicThankYou'])->name('submissions.public.thankyou');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
