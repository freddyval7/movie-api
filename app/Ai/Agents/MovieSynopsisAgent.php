<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Stringable;

class MovieSynopsisAgent implements Agent
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'Eres un experto en cine, genera una breve sinópsis del filme, de dos o tres frases atractivas. En Español. No des spoilers de ningún tipo. Devuelve solo texto, sin comillas ni títulos.';
    }
}
