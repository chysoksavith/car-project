<?php

use Illuminate\Support\Facades\Route;

// Redirect root to admin dashboard
Route::get('/', fn () => redirect('/admin'));

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
