<?php

use App\Ai\Agents\MovieSynopsisAgent;
use App\Jobs\GenerateMovieSynopsis;
use App\Models\Movie;
use App\Services\MovieSynopsisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

it('queues synopsis generation for a movie', function () {
    Queue::fake();

    $movie = Movie::factory()->create(['synopsis' => null]);

    $response = $this->actingAs(editor(), 'api')
        ->postJson("api/v1/movies/{$movie->id}/synopsis");

    $response->assertStatus(202);
    Queue::assertPushed(GenerateMovieSynopsis::class);
    expect($movie->refresh()->synopsis)->toBeNull(); // todavía NO se generó
});

it('generates and saves the synopsis when the job runs', function () {
    MovieSynopsisAgent::fake(['Una sinopsis generada por IA.']);

    $movie = Movie::factory()->create(['synopsis' => null]);

    (new GenerateMovieSynopsis($movie))->handle(app(MovieSynopsisService::class));

    expect($movie->refresh()->synopsis)->toBe('Una sinopsis generada por IA.');
});
