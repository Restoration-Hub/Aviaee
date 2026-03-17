<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('home page can be rendered for guests', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200)
        ->assertViewIs('loginPage');
});

test('registration page can be rendered for guests', function () {
    $response = $this->get(route('registration'));

    $response->assertStatus(200)
        ->assertViewIs('registrationPage');
});

test('authenticated users are redirected from home page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertRedirect(route('missions.list'));
});

test('authenticated users are redirected from registration page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('registration'));

    $response->assertRedirect(route('missions.list'));
});

test('missions list page requires authentication', function () {
    $response = $this->get(route('missions.list'));

    $response->assertRedirect(route('home'));
});

test('missions list page can be rendered for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('missions.list'));

    $response->assertStatus(200)
        ->assertViewIs('missionsPage');
});

test('create mission page requires authentication', function () {
    $response = $this->get(route('create.mission'));

    $response->assertRedirect(route('home'));
});

test('create mission page can be rendered for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('create.mission'));

    $response->assertStatus(200)
        ->assertViewIs('createMissionPage');
});

// test('settings redirect works for authenticated users', function () {
//     $user = User::factory()->create();

//     $response = $this->actingAs($user)->get('/settings');

//     $response->assertRedirect('/settings/profile');
// });