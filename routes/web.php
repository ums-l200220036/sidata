<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataSektoralImportController;
use App\Http\Controllers\AdminIndikatorController;
use App\Http\Controllers\DataSektoralController;

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
        
        Route::get('/indikator', [AdminIndikatorController::class, 'index'])->name('indikator.index');
        Route::post('/indikator', [AdminIndikatorController::class, 'store'])->name('indikator.store');
        Route::put('/indikator/{indikator}', [AdminIndikatorController::class, 'update'])->name('indikator.update');
        Route::delete('/indikator/{indikator}', [AdminIndikatorController::class, 'destroy'])->name('indikator.destroy');
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

Route::get('/tabel', function () {
    return view('tabelbackup');
})->name('tentang');

Route::get('/datasektoral-kecamatan', function () {
    return view('tabel-kecamatan');
})->name('tabel.kecamatan');

Route::get('/datasektoral-kelurahan', function () {
    return view('tabel-kelurahan');
})->name('tabel.kelurahan');

Route::get('/data/gender/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', [DataSektoralController::class, 'showPendidikanByGender'])
    ->middleware('auth')
    ->name('data.gender');

Route::get('/data/pekerjaan/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', [DataSektoralController::class, 'showPekerjaanByGender'])
    ->middleware('auth')
    ->name('data.pekerjaan.gender');

Route::get('/data/agama/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', [DataSektoralController::class, 'showAgamaByGender'])
    ->middleware('auth')
    ->name('data.agama.gender');
