<?php

use App\Ai\Agents\MovieRecommendationAgent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows any authenticated user to get recommendations', function () {
    $recs = [
        ['title' => 'Inception', 'year' => 2010, 'reason' => 'Ciencia ficción sobre la mente'],
        ['title' => 'Interstellar', 'year' => 2014, 'reason' => 'Viaje espacial épico'],
        ['title' => 'The Matrix', 'year' => 1999, 'reason' => 'Acción y filosofía'],
    ];

    MovieRecommendationAgent::fake([$recs]); // tambien funciona con fn () => $recs

    $response = $this->actingAs(viewer(), 'api')
        ->postJson('api/v1/ai/recommendations', ['genres' => ['Acción']]);

    $response->assertOk();
    $response->assertJsonCount(3, 'data.recommendations');
    $response->assertJsonPath('data.recommendations.0.title', 'Inception');
});

it('validates that genres are required for recommendations', function () {
    $this->actingAs(viewer(), 'api')
        ->postJson('api/v1/ai/recommendations', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['genres']);
});
