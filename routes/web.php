<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\ChirpController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/dashboard', [ChirpController::class, 'index'])->name('dashboard');

    Route::resource('chirps', ChirpController::class)->except(['index']);

    Route::post('/logout', Logout::class)->name('logout');
});