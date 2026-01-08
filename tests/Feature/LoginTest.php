<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(WithoutMiddleware::class, RefreshDatabase::class);

test('login verification endpoint returns 200 for valid credentials', function () {
    // Arrange: create user with known password
    $password = 'secret123';

    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make($password),
    ]);

    // Act: send login request
    $response = $this->postJson('/verify-login-credentials', [
        'email' => 'test@example.com',
        'password' => $password,
    ]);

    // Assert
    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Login successful',
            'user_id' => $user->id,
        ]);
});

test('login verification endpoint returns 401 for invalid password', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('correct-password'),
    ]);

    $response = $this->postJson('/verify-login-credentials', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    $response
        ->assertStatus(401)
        ->assertJson([
            'message' => 'Invalid email or password.',
        ]);
});

test('login verification endpoint returns 401 for non existing email in database', function () {
    $response = $this->postJson('/verify-login-credentials', [
        'email' => 'doesnotexist@example.com',
        'password' => 'anything',
    ]);

    $response
        ->assertStatus(401)
        ->assertJson([
            'message' => 'Invalid email or password.',
        ]);
});

test('login verification endpoint returns 422 when email is missing', function () {
    $response = $this->postJson('/verify-login-credentials', [
        'password' => 'secret123',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('login verification endpoint returns 422 when password is missing', function () {
    $response = $this->postJson('/verify-login-credentials', [
        'email' => 'test@example.com',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});
