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
