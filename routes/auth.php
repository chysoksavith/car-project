<?php

use App\Http\Controllers\Auth\V1\LoginController;
use Illuminate\Support\Facades\Route;

// # Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
