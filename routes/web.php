<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return \Inertia\Inertia::render('Frontend/Home');
})->name('home')->middleware('auth');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
