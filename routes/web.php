<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\LoginController;

// Redirect root to admin dashboard
Route::get('/', function () {
    return redirect('/admin');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Dashboard Routes
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Welcome');
    })->name('dashboard');
});
