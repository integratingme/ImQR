<?php

use App\Http\Controllers\Auth\SessionAuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
*/

// Guest-only routes (redirect to dashboard if already authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [SessionAuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [SessionAuthController::class, 'showRegister'])->name('register');
    Route::post('/login', [SessionAuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.attempt');
    Route::post('/register', [SessionAuthController::class, 'register'])
        ->middleware('throttle:3,1')
        ->name('register.store');
});

// Logout route (accessible to everyone)
Route::get('/logout', [SessionAuthController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
