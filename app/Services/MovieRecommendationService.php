<?php

namespace App\Services;

use App\Ai\Agents\MovieRecommendationAgent;
use Laravel\Ai\Enums\Lab;

class MovieRecommendationService
{
    public function generateRecommendations(array $favoriteGenres): array
    {
        $prompt = $this->buildRecommendationsPrompt($favoriteGenres);

        $response = (new MovieRecommendationAgent)->prompt(
            $prompt,
            provider: Lab::OpenRouter,
            model: 'dots-studio/dots-3-note-preview:free',
        );

        return $this->parseRecommendations($response->text);
    }

    private function parseRecommendations(string $raw): array
    {
        $decoded = json_decode(trim($raw), true);
        if (is_array($decoded) && $this->isValidRecommendationArray($decoded)) {
            return $decoded;
        }

        if (preg_match('/\[[\s\S]*?\]/m', $raw, $matches)) {
            $decoded = json_decode($matches[0], true);
            if (is_array($decoded) && $this->isValidRecommendationArray($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    private function isValidRecommendationArray(array $data): bool
    {
        foreach ($data as $item) {
            if (! isset($item['title'], $item['reason'])) {
                return false;
            }
        }

        return count($data) > 0;
    }

    private function buildRecommendationsPrompt(array $favoriteGenres): string
    {
        $genreList = implode(', ', $favoriteGenres);

        // Le pedimos JSON PURO con un formato exacto para poder parsearlo
        return <<<PROMPT
        Eres un experto en cine. Recomienda exactamente 3 películas para alguien que disfruta los géneros: {$genreList}.
        Responde ÚNICAMENTE con un JSON válido con este formato exacto (sin texto adicional, sin markdown):
        [
        {"title": "Nombre de la película", "year": 2020, "reason": "Breve razón de la recomendación"},
        {"title": "Nombre de la película", "year": 1994, "reason": "Breve razón de la recomendación"},
        {"title": "Nombre de la película", "year": 2010, "reason": "Breve razón de la recomendación"}
        ]
        PROMPT;
    }
}
