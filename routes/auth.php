<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\VerifyLoginCredentialsController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Missions\CreateMissionController;
use App\Http\Controllers\Missions\GetMissionsController;
use Illuminate\Support\Facades\Route;
// use Livewire\Volt\Volt;

// GUEST: able to do all login and register tasks, but not access any mission-related endpoints
Route::middleware('guest')->group(function () {
    Route::post('/verify-login-credentials', VerifyLoginCredentialsController::class)
        ->name('verify.login.credentials');

    Route::post('/register', RegisterUserController::class)
        ->name('register');

    // Volt::route('register', 'auth.register')
    //     ->name('register');

    // Volt::route('forgot-password', 'auth.forgot-password')
    //     ->name('password.request');

    // Volt::route('reset-password/{token}', 'auth.reset-password')
    //     ->name('password.reset');
});

// AUTHENTICATED: able to access mission-related endpoints (create and get missions)
Route::middleware('auth')->group(function () {
    Route::post('/missions', CreateMissionController::class)
        ->name('missions.store');

    Route::get('/missions', GetMissionsController::class)
        ->name('missions.get');
});
// TODO: need to validate these routes
Route::middleware('auth')->group(function () {
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Volt::route('verify-email', 'auth.verify-email')
    //     ->name('verification.notice');

    // Volt::route('confirm-password', 'auth.confirm-password')
    //     ->name('password.confirm');
});

// TODO: we have no way for the user to logout currently - need to add a logout button and route
Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');
