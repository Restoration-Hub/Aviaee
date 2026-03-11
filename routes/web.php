<?php

use Illuminate\Support\Facades\Route;
// use Livewire\Volt\Volt;

// ALL: able to access login and registration pages
// Note: authenticated users are redirected to the missions list main page
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('loginPage');
    })->name('home');

    Route::get('/registration', function () {
        return view('registrationPage');
    })->name('registration');
});

// AUTHENTICATED: able to access mission-related pages (and settings pages?)
Route::middleware(['auth'])->group(function () {
    // Route::get('settings', function () {
    //     return redirect()->route('settings.profile');
    // });

    // Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    // Volt::route('settings/password', 'settings.password')->name('settings.password');
    // Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::get('/missions-list', function () {
        return view('missionsPage');
    })->name('missions.list');

    Route::get('/create-mission', function () {
        return view('createMissionPage');
    })->name('create.mission');
});

require __DIR__ . '/auth.php';
