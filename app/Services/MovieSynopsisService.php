<?php

namespace App\Services;

use App\Ai\Agents\MovieSynopsisAgent;
use App\Models\Movie;
use Laravel\Ai\Enums\Lab;

class MovieSynopsisService
{
    public function generateSynopsis(Movie $movie): Movie
    {
        $movie->loadMissing('genres');

        $prompt = $this->buildPrompt($movie);

        $response = (new MovieSynopsisAgent)->prompt(
            $prompt,
            provider: Lab::OpenRouter,
            model: 'dots-studio/dots-3-note-preview:free',
        );

        $movie->synopsis = $response->text;
        $movie->save();

        return $movie;
    }

    private function buildPrompt(Movie $movie): string
    {
        return sprintf(
            "Título: %s\nAño: %d\nDirector: %s\nGéneros: %s",
            $movie->title,
            $movie->year,
            $movie->director ?? 'Desconocido',
            implode(', ', $movie->genres->pluck('name')->toArray())
        );

    }
}
