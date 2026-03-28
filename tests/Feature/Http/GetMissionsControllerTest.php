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

test('returns empty missions array for authenticated user with no missions', function () {
    $user = User::factory()->create(['email' => 'nomissions@example.com']);

    $response = $this->actingAs($user)->get('/missions');

    $response->assertOk()
        ->assertJson([
            'missions' => [],
        ]);
});

test('returns dateDelivered when it is set', function () {
    $user = User::factory()->create(['email' => 'owner@example.com']);

    $mission = Mission::factory()->create([
        'mission_name' => 'Delivered Mission',
        'status' => 'delivered',
        'starting_location' => 'Warehouse',
        'destination' => 'Customer',
        'email' => $user->email,
        'date_delivered' => '2026-03-20 10:30:00',
    ]);

    $response = $this->actingAs($user)->get('/missions');

    $response->assertOk();
    $response->assertJsonCount(1, 'missions');

    $data = $response->json('missions.0');

    expect($data['missionName'])->toBe('Delivered Mission');
    expect($data['status'])->toBe('delivered');
    expect($data['dateDelivered'])->not->toBeNull();
});

test('does not return missions belonging to other users', function () {
    $user = User::factory()->create(['email' => 'owner@example.com']);
    $other = User::factory()->create(['email' => 'other@example.com']);

    Mission::factory()->count(3)->create([
        'email' => $other->email,
    ]);

    $response = $this->actingAs($user)->get('/missions');

    $response->assertOk()
        ->assertJson([
            'missions' => [],
        ]);
});

test('returns correct payload for a single mission', function () {
    $user = User::factory()->create(['email' => 'owner@example.com']);

    Mission::factory()->create([
        'mission_name' => 'Single Mission',
        'status' => 'created',
        'starting_location' => 'Calgary',
        'destination' => 'Edmonton',
        'email' => $user->email,
    ]);

    $response = $this->actingAs($user)->get('/missions');

    $response->assertOk()
        ->assertJsonCount(1, 'missions')
        ->assertJsonFragment([
            'missionName' => 'Single Mission',
            'status' => 'created',
            'startingLocation' => 'Calgary',
            'destination' => 'Edmonton',
            'email' => 'owner@example.com',
        ]);
});

test('every returned mission contains the expected keys', function () {
    $user = User::factory()->create(['email' => 'owner@example.com']);

    Mission::factory()->count(2)->create([
        'email' => $user->email,
    ]);

    $response = $this->actingAs($user)->get('/missions');

    $response->assertOk();

    foreach ($response->json('missions') as $mission) {
        expect($mission)->toHaveKeys([
            'id',
            'missionName',
            'status',
            'startingLocation',
            'destination',
            'email',
            'dateCreated',
            'dateDelivered',
        ]);
    }
});
