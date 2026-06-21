<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// # Admin
Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/', fn () => Inertia::render('Welcome'))->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');

    // Roles & Permissions
    Route::apiResource('roles', RoleController::class)->except('show')->names('admin.roles');
    Route::apiResource('permissions', PermissionController::class)->except('show')->names('admin.permissions');
});
