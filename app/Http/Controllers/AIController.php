<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecommendationsRequest;
use App\Http\Resources\MovieResource;
use App\Jobs\GenerateMovieSynopsis;
use App\Models\Movie;
use App\Services\MovieRecommendationService;
use App\Services\MovieSynopsisService;
use App\Traits\ApiResponse;

class AIController extends Controller
{
    use ApiResponse;

    public function __construct(
        private MovieRecommendationService $movieRecommendationService,
        private MovieSynopsisService $movieSynopsisService,
    ) {}

    // AI Methods
    public function generateSynopsis(Movie $movie)
    {
        GenerateMovieSynopsis::dispatch($movie);

        return $this->successResponse(new MovieResource($movie), 'Synopsis generation queued', 202);
    }

    public function generateRecommendations(RecommendationsRequest $request, MovieRecommendationService $service)
    {
        try {
            $recommendations = $service->generateRecommendations($request->validated('genres'));
        } catch (\Throwable $th) {
            $this->errorResponse('Something went wrong while generating recommendations: '.$th->getMessage(), 502);
        }

        return $this->successResponse(['recommendations' => $recommendations], 'Recommendations generated successfully');
    }
}
