<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgetPasswordController;
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
        Route::delete('/delete-link/{id}', 'destroy');
        Route::post('/toggle-link/{id}', 'toggleLinkStatus');
    });

    Route::controller(AnalyticsController::class)->group(function () {
        Route::get('/overview', 'overview');
        Route::get('/clicks-over-time', 'clicksOverTime');
        Route::get('/links/{id}', 'linkAnalytics');
        Route::get('/recent-clicks', 'recentClicks');
    });
});

Route::get('/{code}', [RedirectController::class, 'redirect']);
Route::controller(ForgetPasswordController::class)->group(function () {
    Route::post('/forgot-password', 'store');
    Route::post('/forgot-password/verify', 'verify');
    Route::post('/reset-password', 'update');

});
