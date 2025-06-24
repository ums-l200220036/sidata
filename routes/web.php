<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Models\DataSektoral;
use App\Models\Indikator;
use App\Http\Controllers\DataSektoralImportController;
use App\Http\Controllers\AdminIndikatorController;
use App\Http\Controllers\DataSektoralController;

Route::get('/', function () {
    // 1. Hitung data langsung di dalam fungsi route
    $totalDataset = DataSektoral::count();
    $totalKategori = Indikator::count();

    // 2. Kirim data tersebut ke view 'home'
    return view('home', [
        'totalDataset' => $totalDataset,
        'totalKategori' => $totalKategori,
    ]);
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


Route::get('/data/gender/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', [DataSektoralController::class, 'showPendidikanByGender'])
    ->middleware('auth')
    ->name('data.gender');

Route::get('/data/pekerjaan/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', [DataSektoralController::class, 'showPekerjaanByGender'])
    ->middleware('auth')
    ->name('data.pekerjaan.gender');

Route::get('/data/agama/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', [DataSektoralController::class, 'showAgamaByGender'])
    ->middleware('auth')
    ->name('data.agama.gender');

Route::get('/laporan/prioritas/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', [DataSektoralController::class, 'showPrioritasReport'])
    ->middleware('auth')
    ->name('laporan.prioritas');

Route::get('/tabel-publik/{indikatorId?}', [DashboardController::class, 'showPublicReport'])->name('laporan.publik');

Route::get('/tabel-publik', function () {
    return view('tabel-publik');
})->name('tabel.publik');

Route::get('/daftar-opd', function () {
    return view('daftar-opd');
})->name('daftar.opd');

Route::get('/laporan/pegawai-usia/{indikatorId}', [DashboardController::class, 'showPegawaiUsiaReport'])
    ->name('laporan.pegawai_usia');
