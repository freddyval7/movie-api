<?php

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns all genres on index', function () {
    Genre::factory()->count(3)->create();
    $response = $this->getJson('api/genres');

    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
});

it('returns a single genre on show', function () {
    $genre = Genre::factory()->create();
    $response = $this->getJson("api/genres/{$genre->id}");

    $response->assertStatus(200);
    $response->assertJsonPath('data.id', $genre->id);
});

it('creates a genre with auto slug on store', function () {
    $payload = [
        'name' => 'Comedy',
        'description' => 'A comedy',
    ];

    $response = $this->postJson('api/genres', $payload);

    $response->assertStatus(201);
    $response->assertJsonPath('data.name', 'Comedy');
    $response->assertJsonPath('data.slug', 'comedy');

    $this->assertDatabaseHas('genres', [
        'name' => 'Comedy',
        'description' => 'A comedy',
        'slug' => 'comedy',
    ]);
});

it('modifies a genre with auto slug on update', function () {
    $genre = Genre::factory()->create([
        'name' => 'Horror',
        'description' => 'A horror',
    ]);

    $response = $this->putJson("api/genres/{$genre->id}", ['name' => 'Classic Horror']);

    $response->assertStatus(200);
    $response->assertJsonPath('data.name', 'Classic Horror');
    $response->assertJsonPath('data.slug', 'classic-horror');

    $this->assertDatabaseHas('genres', [
        'name' => 'Classic Horror',
        'description' => 'A horror',
        'slug' => 'classic-horror',
    ]);
});

it('soft deletes a genre on destroy', function () {
    $genre = Genre::factory()->create();

    $this->deleteJson("api/genres/{$genre->id}");

    $this->assertSoftDeleted('genres', ['id' => $genre->id]);
});
