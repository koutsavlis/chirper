<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/chirps', [ChirpController::class, 'index'])->name('chirps.index');
Route::get('/chirps/{chirp}', [ChirpController::class, 'show'])
    ->whereNumber('chirp')
    ->name('chirps.show');
Route::get('/users/{user}', UserProfileController::class)->name('users.show');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::view('/', 'home')->name('home');

    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', Register::class);

    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', Login::class);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('chirps', ChirpController::class)->except(['index', 'show']);

    Route::post('/logout', Logout::class)->name('logout');
});
