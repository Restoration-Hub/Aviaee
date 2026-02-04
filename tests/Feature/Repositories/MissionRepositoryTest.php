<?php

use App\Domain\Entities\Date;
use App\Domain\Entities\MissionEntity;
use App\Repositories\MissionRepository;
use App\Models\Mission;
use App\Models\User;

/**
 * Test the MissionRepository
 */

test('creates a mission in the database and sets creator email from authenticated user', function () {

    // Arrange: repository and authenticated user
    $repo = new MissionRepository();
    $user = User::factory()->create(['email' => 'creator@example.com']);
    $this->actingAs($user);

    $dateCreated = new Date('2026-01-01 10:00:00');
    $dateDelivered = new Date('2026-01-02 11:30:00');

    $entity = new MissionEntity(
        id: null,
        missionName: 'Test Mission',
        status: 'created',
        startingLocation: 'Start A',
        destination: 'Dest B',
        email: null,
        dateCreated: $dateCreated,
        dateDelivered: $dateDelivered
    );

    // Act: Create the mission
    $result = $repo->create($entity);

    // Assert: Check database and returned entity email matches authenticated user
    $this->assertDatabaseHas('missions', [
        'mission_name' => 'Test Mission',
        'starting_location' => 'Start A',
        'destination' => 'Dest B',
        'status' => 'created',
        'email' => $user->email,
    ]);

    expect($result)->toBeInstanceOf(MissionEntity::class);
    expect($result->id)->toBeInt();
    expect($result->email)->toBe($user->email);
});


test('returns missions for a given email', function () {

    // Arrange: repository and mission test data
    $repo = new MissionRepository();
    $user = User::factory()->create(['email' => 'owner@example.com']);
    $other = User::factory()->create(['email' => 'other@example.com']);

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

    // Act: Get missions for the owner email
    $results = $repo->getMissions($user->email);

    // Assert: Verify correct missions are returned
    expect($results)->toBeArray();
    expect(count($results))->toBe(2);
    expect($results[0])->toBeInstanceOf(\App\Domain\Entities\MissionEntity::class);

    foreach ($results as $r) {
        expect($r->email)->toBe($user->email);
    }
});
