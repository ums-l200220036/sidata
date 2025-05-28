<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\KelurahanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
});



Route::get('/redirect', [UserController::class, 'redirectAfterLogin'])->name('user.redirect');

// Redirect dashboard berdasarkan role user setelah login
Route::get('/dashboard', function () {
    $user = Auth::user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'opd' => redirect()->route('opd.dashboard'),
        'kelurahan' => redirect()->route('kelurahan.dashboard'),
        default => redirect('/'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Group route untuk profile user (semua user yang login bisa akses)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Group route untuk admin dengan middleware role
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    // Tambahkan route admin lainnya di sini
});

// Group route untuk opd dengan middleware role
Route::middleware(['auth', 'role:opd'])->prefix('opd')->name('opd.')->group(function () {
    Route::get('/dashboard', [OpdController::class, 'index'])->name('dashboard');
    // Tambahkan route opd lainnya di sini
});

// Group route untuk kelurahan dengan middleware role
Route::middleware(['auth', 'role:kelurahan'])->prefix('kelurahan')->name('kelurahan.')->group(function () {
    Route::get('/dashboard', [KelurahanController::class, 'index'])->name('dashboard');
    // Tambahkan route kelurahan lainnya di sini
});

require __DIR__.'/auth.php';