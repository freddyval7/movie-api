<?php

it('returns ok on health endpoint', function () {
    $response = $this->get('/api/health');

    $response->assertStatus(200);
    $response->assertJson(
        ['data' => ['status' => 'ok', 'app' => 'movie-api'],
            'message' => 'Health Check']
    );
});
