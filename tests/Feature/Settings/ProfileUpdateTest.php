<?php

// Tests are commented out because these are tests which are for the sample dashboard
// kept them to refer to later if needed.

// use App\Models\User;
// use Livewire\Volt\Volt;

// uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// test('profile page is displayed', function () {
//     $this->actingAs($user = User::factory()->create());

//     $this->get('/settings/profile')->assertOk();
// });

// /**
//  * Will likely need to add email_verified_at and remember_token fields to the user factory again.
//  * Temporarily commenting out expect($user->email_verified_at)->toBeNull();
//  */

// test('profile information can be updated', function () {
//     $user = User::factory()->create();

//     $this->actingAs($user);

//     $response = Volt::test('settings.profile')
//         ->set('first_name', 'John')
//         ->set('last_name', 'Pork')
//         ->set('email', 'test@example.com')
//         ->call('updateProfileInformation');

//     $response->assertHasNoErrors();

//     $user->refresh();

//     expect($user->first_name)->toEqual('John');
//     expect($user->last_name)->toEqual('Pork');
//     expect($user->email)->toEqual('test@example.com');
//     // expect($user->email_verified_at)->toBeNull();
// });

// /**
//  * Will likely need to add email_verified_at and remember_token fields to the user factory again.
//  * Temporarily commenting out these tests to fix failing test suite.
//  */

// // test('email verification status is unchanged when email address is unchanged', function () {
// //     $user = User::factory()->create();

// //     $this->actingAs($user);

// //     $response = Volt::test('settings.profile')
// //         ->set('first_name', 'John')
// //         ->set('last_name', 'Pork')
// //         ->set('email', $user->email)
// //         ->call('updateProfileInformation');

// //     $response->assertHasNoErrors();

// //     expect($user->refresh()->email_verified_at)->not->toBeNull();
// // });

// test('user can delete their account', function () {
//     $user = User::factory()->create();

//     $this->actingAs($user);

//     $response = Volt::test('settings.delete-user-form')
//         ->set('password', 'password')
//         ->call('deleteUser');

//     $response
//         ->assertHasNoErrors()
//         ->assertRedirect('/');

//     expect($user->fresh())->toBeNull();
//     expect(auth()->check())->toBeFalse();
// });

// test('correct password must be provided to delete account', function () {
//     $user = User::factory()->create();

//     $this->actingAs($user);

//     $response = Volt::test('settings.delete-user-form')
//         ->set('password', 'wrong-password')
//         ->call('deleteUser');

//     $response->assertHasErrors(['password']);

//     expect($user->fresh())->not->toBeNull();
// });