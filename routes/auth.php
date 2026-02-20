<?php

use App\Http\Controllers\Auth\FirebaseAuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Firebase Authentication routes. Login/Register pages serve the Firebase
| JS SDK UI. The callback endpoint verifies Firebase ID tokens and
| creates Laravel sessions.
|
*/

// Guest-only routes (redirect to dashboard if already authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [FirebaseAuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [FirebaseAuthController::class, 'showRegister'])->name('register');
});

// Firebase token callback (accessible to both guests and authenticated users)
Route::post('/auth/firebase/callback', [FirebaseAuthController::class, 'handleCallback'])
    ->name('auth.firebase.callback');

// Logout route (accessible to everyone)
Route::get('/logout', [FirebaseAuthController::class, 'showLogout'])->name('logout');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
