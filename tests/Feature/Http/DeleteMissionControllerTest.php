<?php

use App\Models\Mission;
use App\Models\User;

/**
 * Test DeleteMissionController
 */
test('authenticated user can delete a mission', function () {
    $user = User::factory()->create();

    $mission = Mission::factory()->create();

    $response = $this->actingAs($user)->delete('/missions/delete', [
        'missionId' => $mission->id,
    ]);

    $response->assertOk()
        ->assertJson([
            'message' => 'Mission deleted successfully!',
        ]);

    $this->assertDatabaseMissing('missions', [
        'id' => $mission->id,
    ]);
});

test('missionId is required', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->delete('/missions/delete', []);

    $response->assertSessionHasErrors(['missionId']);
});

test('missionId must be an integer', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->delete('/missions/delete', [
        'missionId' => 'not-an-integer',
    ]);

    $response->assertSessionHasErrors(['missionId']);
});

test('missionId must exist in missions table', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->delete('/missions/delete', [
        'missionId' => 999999,
    ]);

    $response->assertSessionHasErrors(['missionId']);
});

test('deleting one mission does not delete other missions', function () {
    $user = User::factory()->create();

    $missionToDelete = Mission::factory()->create();
    $missionToKeep = Mission::factory()->create();

    $response = $this->actingAs($user)->delete('/missions/delete', [
        'missionId' => $missionToDelete->id,
    ]);

    $response->assertOk();

    $this->assertDatabaseMissing('missions', [
        'id' => $missionToDelete->id,
    ]);

    $this->assertDatabaseHas('missions', [
        'id' => $missionToKeep->id,
    ]);
});
