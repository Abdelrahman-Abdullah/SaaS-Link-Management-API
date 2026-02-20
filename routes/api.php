<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShortLinkGeneratorController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::post('/generate', [ShortLinkGeneratorController::class, 'generate'])->middleware('auth:sanctum');