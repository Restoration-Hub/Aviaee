<?php

use App\Domain\Entities\Date;
use App\Domain\Entities\MissionEntity;
use App\Repositories\MissionRepository;
use App\Models\Mission;
use App\Models\User;

/**
 * Test the MissionRepository::create method
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
