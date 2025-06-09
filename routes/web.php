<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataSektoralImportController;

Route::get('/', function () {
    return view('home');
});


Route::get('/redirect', [UserController::class, 'redirectAfterLogin'])->name('user.redirect');


// Redirect dashboard berdasarkan role user setelah login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users', [AdminController::class, 'index'])->name('users.index');
        Route::post('/users', [AdminController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [AdminController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware(['auth', 'role:opd'])->group(function () {
        Route::get('/unggah-data', [DataSektoralImportController::class, 'form'])->name('data.form');
        Route::post('/unggah-data', [DataSektoralImportController::class, 'import'])->name('data.import');
    });
});

require __DIR__.'/auth.php';

Route::get('/tentang', function () {
    return view('tentang');
})->name('tentang');

Route::get('/kelolakategori', function () {
    return view('admin/kelolakategori');
})->name('kelolakategori');
