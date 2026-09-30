<?php

use App\Ai\Agents\MovieSynopsisAgent;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates and saves a synopsis for a movie', function () {
    MovieSynopsisAgent::fake(['Una sinopsis generada por IA.']);

    $movie = Movie::factory()->create();

    $response = $this->actingAs(editor(), 'api')
        ->postJson("api/v1/movies/{$movie->id}/synopsis");

    $response->assertOk();
    $response->assertJsonPath('data.synopsis', 'Una sinopsis generada por IA.');

    $this->assertDatabaseHas('movies', [
        'id' => $movie->id,
        'synopsis' => 'Una sinopsis generada por IA.',
    ]);

    MovieSynopsisAgent::assertPrompted(fn ($prompt) => $prompt->contains($movie->title));
});
