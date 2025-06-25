<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminIndikatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataSektoralController;
use App\Http\Controllers\DataSektoralImportController;
use App\Http\Controllers\UserController;
use App\Models\DataSektoral;
use App\Models\Indikator;
use Illuminate\Support\Facades\Route;


//======================================================================
// PUBLIC ROUTES
// Rute yang dapat diakses oleh semua pengunjung.
//======================================================================

Route::get('/', function () {
    return view('home', [
        'totalDataset' => DataSektoral::count(),
        'totalKategori' => Indikator::count(),
    ]);
});

Route::view('/tentang', 'tentang')->name('tentang');
Route::view('/daftar-opd', 'daftar-opd')->name('daftar.opd');

// Catatan: Rute spesifik '/tabel-publik' ditempatkan sebelum yang berparameter
// untuk memastikan rute ini dicocokkan dengan benar oleh Laravel.

Route::controller(DashboardController::class)->group(function () {
    Route::get('/tabel-publik/{indikatorId?}', 'showPublicReport')->name('laporan.publik');
    Route::get('/laporan-export/publik/{indikatorId?}', 'exportPublicReport')->name('laporan.export.publik');
});


//======================================================================
// AUTHENTICATION ROUTES
// Rute untuk login, registrasi, reset password, dll.
//======================================================================

require __DIR__.'/auth.php';


//======================================================================
// AUTHENTICATED ROUTES
// Rute yang memerlukan pengguna untuk login.
//======================================================================

Route::middleware(['auth', 'verified'])->group(function () {

    // Pengalihan setelah login berdasarkan peran pengguna
    Route::get('/redirect', [UserController::class, 'redirectAfterLogin'])->name('user.redirect');
    
    // Dasbor umum setelah login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- ADMIN ONLY ROUTES ---
    Route::middleware(['role:admin'])->group(function () {

        // Manajemen Pengguna (Users)
        Route::controller(AdminController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{user}', 'update')->name('update');
            Route::delete('/{user}', 'destroy')->name('destroy');
        });

        // Manajemen Indikator
        Route::controller(AdminIndikatorController::class)->prefix('indikator')->name('indikator.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{indikator}', 'update')->name('update');
            Route::delete('/{indikator}', 'destroy')->name('destroy');
        });
    });

    // --- OPD ONLY ROUTES ---
    Route::middleware(['role:opd'])->group(function () {
        Route::get('/unggah-data', [DataSektoralImportController::class, 'form'])->name('data.form');
        Route::post('/unggah-data', [DataSektoralImportController::class, 'import'])->name('data.import');
    });
    
    // --- DATA & REPORTING ROUTES ---
    Route::controller(DataSektoralController::class)->group(function() {
        // Tampilan Data
        Route::get('/data/gender/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', 'showPendidikanByGender')->name('data.gender');
        Route::get('/data/pekerjaan/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', 'showPekerjaanByGender')->name('data.pekerjaan.gender');
        Route::get('/data/agama/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', 'showAgamaByGender')->name('data.agama.gender');

        // Tampilan Laporan
        Route::get('/laporan/prioritas/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', 'showPrioritasReport')->name('laporan.prioritas');
        Route::get('/laporan/pegawai-usia/{indikatorId}', 'showPegawaiUsiaReport')->name('laporan.pegawai_usia');

        // Ekspor Laporan
        Route::prefix('laporan-export')->name('laporan.export.')->group(function () {
            Route::get('prioritas/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', 'exportPrioritas')->name('prioritas');
            Route::get('pendidikan-gender/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', 'exportPendidikanByGender')->name('pendidikan.gender');
            Route::get('pekerjaan-gender/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', 'exportPekerjaanByGender')->name('pekerjaan.gender');
            Route::get('agama-gender/{indikatorId}/{tahun?}/{kecamatanId?}/{kelurahanId?}', 'exportAgamaByGender')->name('agama.gender');
            Route::get('pegawai-usia/{indikatorId}', 'exportPegawaiUsiaReport')->name('pegawai_usia');
        });
    });
});