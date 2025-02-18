<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DasboradController;
Route::view('/', 'welcome');

Route::get('dashboard', [DasboradController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
