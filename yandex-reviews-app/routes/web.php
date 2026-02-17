<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->prefix('api')->group(function (): void {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:web')->group(function (): void {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/settings', [SettingsController::class, 'show']);
        Route::put('/settings', [SettingsController::class, 'update']);

        Route::get('/reviews', [ReviewController::class, 'index']);
    });
});

Route::view('/{any?}', 'app')->where('any', '.*');
