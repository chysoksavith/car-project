<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\V1\LoginController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Redirect root to admin dashboard
Route::get('/', fn () => redirect('/admin'));

// ── Authentication ────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Admin ─────────────────────────────────────────────────────────────────
Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/', fn () => Inertia::render('Welcome'))->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');

    // Roles (index + store + update + destroy)
    Route::get('/roles',              [RoleController::class, 'index'])->name('admin.roles.index');
    Route::post('/roles',             [RoleController::class, 'store'])->name('admin.roles.store');
    Route::put('/roles/{role}',       [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/roles/{role}',    [RoleController::class, 'destroy'])->name('admin.roles.destroy');

    // Permissions (index + store + update + destroy)
    Route::get('/permissions',                  [PermissionController::class, 'index'])->name('admin.permissions.index');
    Route::post('/permissions',                 [PermissionController::class, 'store'])->name('admin.permissions.store');
    Route::put('/permissions/{permission}',     [PermissionController::class, 'update'])->name('admin.permissions.update');
    Route::delete('/permissions/{permission}',  [PermissionController::class, 'destroy'])->name('admin.permissions.destroy');
});
