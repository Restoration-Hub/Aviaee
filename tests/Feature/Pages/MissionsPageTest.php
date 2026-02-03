<?php

use App\Models\Mission;
use App\Models\User;

/**
 * Test the missions view for authenticated user
 */

test('missions page shows missions for authenticated user', function () {
    $user = User::factory()->create(['email' => 'owner@example.com']);

    Mission::factory()->create([
        'mission_name' => 'Owner Mission 1',
        'status' => 'created',
        'starting_location' => 'Start 1',
        'destination' => 'Dest 1',
        'email' => $user->email,
    ]);

    // Sanity-check the repository directly
    $repo = new \App\Repositories\MissionRepository();
    $found = $repo->getMissions($user->email);
    expect(count($found))->toBe(1);

    $response = $this->actingAs($user)->get('/missions-list');

    $response->assertOk();
    // The page should render the Livewire missions component
    $response->assertSee('missions-table-component');
});
