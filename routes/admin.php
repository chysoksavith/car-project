<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// # Admin
Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\CheckBackendAccess::class])->group(function () {

    Route::get('/', fn () => Inertia::render('Welcome'))->name('dashboard');

    // Users
    Route::resource('users', UserController::class)->except('show')->names('admin.users');

    // Roles & Permissions
    Route::apiResource('roles', RoleController::class)->except('show')->names('admin.roles');
    Route::apiResource('permissions', PermissionController::class)->except('show')->names('admin.permissions');

    // Locations
    Route::get('locations', [\App\Http\Controllers\Admin\LocationController::class, 'index'])->name('admin.locations.index');

    // Companies
    Route::resource('companies', \App\Http\Controllers\Admin\CompanyController::class)->except('show')->names('admin.companies');

    // Makers
    Route::resource('makers', \App\Http\Controllers\Admin\MakerController::class)->except('show')->names('admin.makers');

    // Car Models
    Route::resource('car-models', \App\Http\Controllers\Admin\CarModelController::class)->except('show')->names('admin.car-models');

    // Fuels
    Route::resource('fuels', \App\Http\Controllers\Admin\FuelController::class)->except('show')->names('admin.fuels');

    // Colors
    Route::resource('colors', \App\Http\Controllers\Admin\ColorController::class)->except('show')->names('admin.colors');

    // Suppliers
    Route::resource('suppliers', \App\Http\Controllers\Admin\SupplierController::class)->except(['show'])->names('admin.suppliers');

    // Inspection Items
    Route::resource('inspection-items', \App\Http\Controllers\Admin\InspectionItemController::class)->except(['show'])->names('admin.inspection-items');

    // Car Inspections
    Route::get('car-inspections', [\App\Http\Controllers\Admin\CarInspectionController::class, 'index'])->name('admin.car-inspections.index');
    Route::post('car-inspections/initialize', [\App\Http\Controllers\Admin\CarInspectionController::class, 'initialize'])->name('admin.car-inspections.initialize');
    Route::put('car-inspections/{carInspection}/cost', [\App\Http\Controllers\Admin\CarInspectionController::class, 'updateCost'])->name('admin.car-inspections.update-cost');
    Route::put('car-inspections/{carInspection}/details', [\App\Http\Controllers\Admin\CarInspectionController::class, 'updateDetails'])->name('admin.car-inspections.update-details');

    // Containers
    Route::resource('containers', \App\Http\Controllers\Admin\ContainerController::class)->except(['show'])->names('admin.containers');

    // Departments
    Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class)->except('show')->names('admin.departments');

    // Cars
    Route::resource('cars', \App\Http\Controllers\Admin\CarController::class)->names('admin.cars');
});
