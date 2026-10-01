<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Stringable;

class MovieRecommendationAgent implements Agent
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'Eres un experto en cine. Recomienda exactamente 3 películas para los géneros que te hacen llegar, en español. No des spoilers de ningún tipo. Devuelve solo texto, sin comillas ni títulos.';
    }
}
