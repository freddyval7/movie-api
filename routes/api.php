<?php

use App\Http\Controllers\GenreController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class);

Route::apiResource('genres', GenreController::class);

Route::post('/genres/{id}/restore', [GenreController::class, 'restore']);

Route::get('/genres/slug/{slug}', [GenreController::class, 'showBySlug']);
