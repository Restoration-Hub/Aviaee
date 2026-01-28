<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('Registration endpoint returns 200 for successful registration', function () {
    $response = $this->postJson('/register', [
        'first_name'   => 'steve',
        'last_name'    => 'jobs',
        'phone_number' => '8255044033',
        'user_type'    => 'buyer',
        'email'        => 'steve@example.com',
        'password'     => 'secret123',
    ]);

    $response
        ->assertStatus(201)
        ->assertJson([
            'message' => 'Registration successful',
            'user_id' => 1,
        ]);
});

test('Registration endpoint returns 422 when field is missing', function () {
    $response = $this->postJson('/register', [
        'first_name'   => 'steve',
        'last_name'    => 'jobs',
        'phone_number' => '8255044033',
        'user_type'    => 'buyer',
        // 'email' is missing
        'password'     => 'secret123',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => ['email'],
        ]);
});

