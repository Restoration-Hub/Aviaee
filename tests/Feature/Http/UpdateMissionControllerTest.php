<?php

use App\Enums\MissionStatus;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can update a mission status', function () {
    $user = User::factory()->create();

    $mission = Mission::factory()->create([
        'status' => MissionStatus::ORDERED,
    ]);

    $payload = [
        'missionId' => $mission->id,
        'status' => MissionStatus::PACKED->value,
    ];

    $response = $this->actingAs($user)->putJson('/missions/updateStatus', $payload);

    $response->assertOk()
        ->assertJson([
            'message' => 'Mission updated successfully!',
        ]);

    $this->assertDatabaseHas('missions', [
        'id' => $mission->id,
        'status' => MissionStatus::PACKED->value,
    ]);
});

test('authenticated user can update mission to delivered', function () {
    $user = User::factory()->create();

    $mission = Mission::factory()->create([
        'status' => MissionStatus::IN_TRANSIT,
    ]);

    $payload = [
        'missionId' => $mission->id,
        'status' => MissionStatus::DELIVERED->value,
    ];

    $response = $this->actingAs($user)->putJson('/missions/updateStatus', $payload);

    $response->assertOk()
        ->assertJson([
            'message' => 'Mission updated successfully!',
        ]);

    $this->assertDatabaseHas('missions', [
        'id' => $mission->id,
        'status' => MissionStatus::DELIVERED->value,
    ]);
});

test('mission update fails with invalid status', function () {
    $user = User::factory()->create();

    $mission = Mission::factory()->create([
        'status' => MissionStatus::ORDERED,
    ]);

    $payload = [
        'missionId' => $mission->id,
        'status' => 'invalid',
    ];

    $response = $this->actingAs($user)->putJson('/missions/updateStatus', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['status']);
});

test('mission update fails if mission does not exist', function () {
    $user = User::factory()->create();

    $payload = [
        'missionId' => 99999,
        'status' => MissionStatus::PACKED->value,
    ];

    $response = $this->actingAs($user)->putJson('/missions/updateStatus', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['missionId']);
});
