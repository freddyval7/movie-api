<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\GenreController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\MovieController;
use App\Http\Controllers\Api\V2\MovieController as MovieControllerV2;
use App\Http\Controllers\PosterController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::get('/health', HealthController::class);

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);

        Route::post('login', [AuthController::class, 'login']);
    });

    // Genres
    Route::get('/genres', [GenreController::class, 'index']);
    Route::get('/genres/slug/{slug}', [GenreController::class, 'showBySlug']);
    Route::get('/genres/{genre}', [GenreController::class, 'show']);

    // Movies
    Route::get('/movies', [MovieController::class, 'index']);
    Route::get('/movies/{movie}', [MovieController::class, 'show']);

    // Protected routes
    Route::middleware('auth:api')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);

            Route::post('refresh', [AuthController::class, 'refresh']);

            Route::get('me', [AuthController::class, 'me']);
        });

        // Genres
        Route::middleware('role:admin,editor')->group(function () {
            Route::post('genres', [GenreController::class, 'store']);
            Route::put('genres/{genre}', [GenreController::class, 'update']);
            Route::patch('genres/{genre}', [GenreController::class, 'update']);
        });

        Route::middleware('role:admin')->group(function () {
            Route::delete('genres/{genre}', [GenreController::class, 'destroy']);
            Route::post('/genres/{id}/restore', [GenreController::class, 'restore']);
        });

        // Movies
        Route::middleware('role:admin,editor')->group(function () {
            Route::post('movies', [MovieController::class, 'store']);
            Route::put('movies/{movie}', [MovieController::class, 'update']);
            Route::patch('movies/{movie}', [MovieController::class, 'update']);
            Route::post('movies/{movie}/poster', [PosterController::class, 'store']);
            Route::post('movies/{movie}/synopsis', [MovieController::class, 'generateSynopsis']);
        });

        Route::middleware('role:admin')->group(function () {
            Route::delete('movies/{movie}', [MovieController::class, 'destroy']);
            Route::post('/movies/{id}/restore', [MovieController::class, 'restore']);
        });
    });
});

Route::prefix('v2')->group(function () {
    Route::get('/movies', [MovieControllerV2::class, 'index']);
    Route::get('/movies/{movie}', [MovieControllerV2::class, 'show']);
});
