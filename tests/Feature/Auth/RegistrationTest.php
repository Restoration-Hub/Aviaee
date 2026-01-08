<?php

// Tests are commented out because these are tests which are for the sample dashboard
// kept them to refer to later if needed.

// use Livewire\Volt\Volt;

// uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// test('registration screen can be rendered', function () {
//     $response = $this->get('/register');

//     $response->assertStatus(200);
// });

// test('new users can register', function () {
//     $response = Volt::test('auth.register')
//         ->set('first_name', 'John')
//         ->set('last_name', 'Pork')
//         ->set('phone_number', '1234567890')
//         ->set('email', 'test@example.com')
//         ->set('password', 'password')
//         ->set('password_confirmation', 'password')
//         ->call('register');

//     $response
//         ->assertHasNoErrors()
//         ->assertRedirect(route('dashboard', absolute: false));

//     $this->assertAuthenticated();
// });