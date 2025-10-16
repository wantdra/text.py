<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\LearnController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/learn/next', [LearnController::class, 'next'])->name('api.learn.next');
    Route::post('/learn/answer', [LearnController::class, 'answer'])->name('api.learn.answer');
    Route::get('/stats/overview', [StatsController::class, 'overview']);
    Route::get('/games/pool', [GameController::class, 'pool']);
    Route::post('/games/score', [GameController::class, 'score']);
});
