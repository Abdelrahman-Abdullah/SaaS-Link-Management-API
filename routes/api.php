<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\ShortLinkController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(ShortLinkController::class)->group(function () {
        Route::get('/links', 'index');
        Route::post('/generate', 'store');
        Route::delete('/delete/{id}', 'destroy');
    });
});

Route::get('/{code}', [RedirectController::class, 'redirect']);
