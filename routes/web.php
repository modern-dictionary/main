<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GeneralController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\S3Controller;



Route::get('/', [ReportController::class, 'landingData'])->name('landing');
Route::get('/library', [GeneralController::class, 'index']);
Route::get('/search', [GeneralController::class, 'search']);


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
    Route::get('/categories/{id}/words', [CategoryController::class, 'getCategoryWords']); //get the words of a category
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update'); // Update category
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy'); // Delete category

    // Team Management Routes
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::post('/teams/{team}/join-request', [TeamController::class, 'sendJoinRequest'])->name('teams.join-request');
    Route::delete('/teams/{team}/leave-team', [TeamController::class, 'leave'])->name('teams.leave-team');

    // Chart data
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])
        ->name('dashboard.chart-data');

});

// cloud storage links
Route::get('/userinterface', [S3Controller::class, 'showUserInterface'])->name('user.interface');
Route::post('/upload-file', [S3Controller::class, 'uploadFile'])->name('upload.file');
Route::post('/show-objects', [S3Controller::class, 'showObjects'])->name('show.objects');
Route::post('/retrieve-file', [S3Controller::class, 'retrieveFile'])->name('retrieve.file');
Route::post('/delete-file', [S3Controller::class, 'deleteFile'])->name('delete.file');
Route::post('/download-file', [S3Controller::class, 'downloadFile'])->name('download.file');
