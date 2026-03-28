<?php

use App\Models\User;

/**
 * Test CreateMissionController
 */
test('authenticated user can create a mission and email is set', function () {
    $user = User::factory()->create(['email' => 'creator@example.com']);

    $payload = [
        'missionName' => 'New Mission',
        'startingLocation' => 'Start X',
        'destination' => 'Dest Y',
    ];

    $response = $this->actingAs($user)->post('/missions', $payload);

    $response->assertOk();
    $this->assertDatabaseHas('missions', [
        'mission_name' => 'New Mission',
        'starting_location' => 'Start X',
        'destination' => 'Dest Y',
        'email' => $user->email,
    ]);
});

test('missionName is required', function () {
    $user = User::factory()->create();

    $payload = [
        'startingLocation' => 'Start X',
        'destination' => 'Dest Y',
    ];

    $response = $this->actingAs($user)->post('/missions', $payload);

    $response->assertSessionHasErrors(['missionName']);
    $this->assertDatabaseCount('missions', 0);
});

test('startingLocation is required', function () {
    $user = User::factory()->create();

    $payload = [
        'missionName' => 'New Mission',
        'destination' => 'Dest Y',
    ];

    $response = $this->actingAs($user)->post('/missions', $payload);

    $response->assertSessionHasErrors(['startingLocation']);
    $this->assertDatabaseCount('missions', 0);
});

test('destination is required', function () {
    $user = User::factory()->create();

    $payload = [
        'missionName' => 'New Mission',
        'startingLocation' => 'Start X',
    ];

    $response = $this->actingAs($user)->post('/missions', $payload);

    $response->assertSessionHasErrors(['destination']);
    $this->assertDatabaseCount('missions', 0);
});

test('missionName must be a string', function () {
    $user = User::factory()->create();

    $payload = [
        'missionName' => ['not', 'a', 'string'],
        'startingLocation' => 'Start X',
        'destination' => 'Dest Y',
    ];

    $response = $this->actingAs($user)->post('/missions', $payload);

    $response->assertSessionHasErrors(['missionName']);
    $this->assertDatabaseCount('missions', 0);
});

test('startingLocation must not exceed 255 characters', function () {
    $user = User::factory()->create();

    $payload = [
        'missionName' => 'New Mission',
        'startingLocation' => str_repeat('A', 256),
        'destination' => 'Dest Y',
    ];

    $response = $this->actingAs($user)->post('/missions', $payload);

    $response->assertSessionHasErrors(['startingLocation']);
    $this->assertDatabaseCount('missions', 0);
});

test('destination must not exceed 255 characters', function () {
    $user = User::factory()->create();

    $payload = [
        'missionName' => 'New Mission',
        'startingLocation' => 'Start X',
        'destination' => str_repeat('B', 256),
    ];

    $response = $this->actingAs($user)->post('/missions', $payload);

    $response->assertSessionHasErrors(['destination']);
    $this->assertDatabaseCount('missions', 0);
});

test('response includes created mission data', function () {
    $user = User::factory()->create(['email' => 'creator@example.com']);

    $payload = [
        'missionName' => 'Mission Alpha',
        'startingLocation' => 'Calgary',
        'destination' => 'Edmonton',
    ];

    $response = $this->actingAs($user)->post('/missions', $payload);

    $response->assertOk()
        ->assertJsonFragment([
            'mission_name' => 'Mission Alpha',
            'starting_location' => 'Calgary',
            'destination' => 'Edmonton',
            'email' => 'creator@example.com',
        ]);
});
