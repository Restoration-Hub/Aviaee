<?php

use App\Models\Mission;
use App\Models\User;
use Livewire\Livewire;

/**
 * Livewire MissionsTable component tests
 */

test('livewire missions table shows user missions', function () {
    $user = User::factory()->create(['email' => 'owner@example.com']);

    Mission::factory()->create([
        'mission_name' => 'Owner Mission 1',
        'status' => 'created',
        'starting_location' => 'Start 1',
        'destination' => 'Dest 1',
        'email' => $user->email,
    ]);

    $this->actingAs($user);

    Livewire::test(\App\Http\Livewire\MissionsTable::class)
        ->assertSee('Owner Mission 1');
});
