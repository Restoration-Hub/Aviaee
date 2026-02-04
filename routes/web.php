<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('loginPage');
})->name('home');

Route::get('/registration', function () {
    return view('registrationPage');
})->name('registration');

Route::get('/create-mission', function () {
    return view('createMissionPage');
})->name('create.mission');

// TODO: Remove this page route after implementing full auth flow
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::get('/missions-list', function () {
        return view('missionsPage');
    })->name('missions.list');
});

require __DIR__ . '/auth.php';
