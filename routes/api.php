<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class);

Route::apiResource('genres', GenreController::class);

Route::post('/genres/{id}/restore', [GenreController::class, 'restore']);

Route::get('/genres/slug/{slug}', [GenreController::class, 'showBySlug']);

Route::apiResource('movies', MovieController::class);

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);

    Route::post('login', [AuthController::class, 'login']);
});

// Protected routes
Route::prefix('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::get('me', [AuthController::class, 'me']);
});
