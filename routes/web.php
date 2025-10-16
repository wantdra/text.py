<?php

use App\Http\Controllers\LearnController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::view('/', 'dashboard.index')->name('dashboard');
    Route::get('/learn', [LearnController::class, 'index'])->name('learn');
});

require __DIR__.'/auth.php';
