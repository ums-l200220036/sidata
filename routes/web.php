<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/unggah-data', function () {
    return view('user.unggahdata');
})->name('opd.unggah');

Route::get('/redirect', [UserController::class, 'redirectAfterLogin'])->name('user.redirect');


// Redirect dashboard berdasarkan role user setelah login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

require __DIR__.'/auth.php';