<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;


Route::get('/', [ReportController::class, 'landingData'])->name('landing');


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function ()
{
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Words Management Routes
    Route::get('/words', [WordController::class, 'userWords'])->name('words.index');
    Route::post('/words', [WordController::class, 'store'])->name('words.store');
    Route::get('/words/{word}', [WordController::class, 'show'])->name('words.show'); // Show specific word
    Route::put('/words/{word}', [WordController::class, 'update'])->name('words.update'); // Update word
    Route::delete('/words/{word}', [WordController::class, 'destroy'])->name('words.destroy'); // Delete word

    // Category Management Routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show'); // Show specific category
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update'); // Update category
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy'); // Delete category
});
