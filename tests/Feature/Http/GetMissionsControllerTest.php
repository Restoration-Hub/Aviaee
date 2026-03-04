<?php

use App\Models\Mission;
use App\Models\User;

/**
 * Test the GetMissionsController
 */

test('returns missions for authenticated user', function () {
    $user = User::factory()->create(['email' => 'owner@example.com']);
    $other = User::factory()->create(['email' => 'other@example.com']);

    // Create missions for owner and one for another user
    Mission::factory()->create([
        'mission_name' => 'Owner Mission 1',
        'status' => 'created',
        'starting_location' => 'Start 1',
        'destination' => 'Dest 1',
        'email' => $user->email,
    ]);

    Mission::factory()->create([
        'mission_name' => 'Owner Mission 2',
        'status' => 'created',
        'starting_location' => 'Start 2',
        'destination' => 'Dest 2',
        'email' => $user->email,
    ]);

    Mission::factory()->create([
        'mission_name' => 'Other Mission',
        'status' => 'created',
        'starting_location' => 'Other Start',
        'destination' => 'Other Dest',
        'email' => $other->email,
    ]);

    $response = $this->actingAs($user)->get('/missions');

    $response->assertOk();
    $response->assertJsonCount(2, 'missions');

    $data = $response->json('missions');
    foreach ($data as $m) {
        expect($m['email'])->toBe($user->email);
        expect(array_keys($m))->toEqual([
            'id',
            'missionName',
            'status',
            'startingLocation',
            'destination',
            'email',
            'dateCreated',
            'dateDelivered',
        ]);
        // dateDelivered should be null when not set
        expect($m['dateDelivered'])->toBeNull();
    }
});
