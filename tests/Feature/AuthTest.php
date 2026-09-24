<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates user and returns token on register', function () {
    $payload = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->actingAs(editor(), 'api')->post('/api/auth/register', $payload);

    $response->assertStatus(201);
    $response->assertJsonStructure([
        'data' => [
            'access_token',
            'token_type',
            'expires_in',
            'user',
        ],
    ]);
    $response->assertJsonPath('data.user.email', 'john@example.com');
});

it('does not expose password on register', function () {
    $payload = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->post('/api/auth/register', $payload);

    $response->assertStatus(201);
    expect($response->json('data.user'))->not->toHaveKey('password');
});

it('requires all fields on register', function () {
    $response = $this->post('/api/auth/register', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('returns token for valid credentials on login', function () {
    $user = User::factory()->create(
        [
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]
    );

    $response = $this->postJson('/api/auth/login', [
        'email' => 'john@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'access_token',
            'token_type',
            'expires_in',
            'user',
        ],
    ]);
    $response->assertJsonPath('data.user.id', $user->id);
});

it('returns user with valid token on me', function () {
    $this->actingAs(admin(), 'api')
        ->getJson('/api/auth/me')
        ->assertStatus(200);
});

it('succedes with valid token on logout', function () {
    $this->actingAs(admin(), 'api')
        ->postJson('/api/auth/logout')
        ->assertStatus(200);
});
