<?php

namespace App\Jobs;

use App\Models\Movie;
use App\Services\MovieSynopsisService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateMovieSynopsis implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [30, 60, 120];

    public function __construct(public Movie $movie) {}

    public function handle(MovieSynopsisService $service): void
    {
        $service->generateSynopsis($this->movie);
    }
}
