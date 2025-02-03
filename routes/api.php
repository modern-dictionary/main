<?php

use App\Http\Controllers\Api\WordImportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/import-word/{word}', [WordImportController::class, 'importWord']);
