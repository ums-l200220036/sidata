<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan daftar indikator
     * yang sudah difilter berdasarkan hak akses pengguna.
     */
    public function index()
    {
        // 1. Ambil data pengguna yang sedang login
        $user = Auth::user();
        
        // 2. Siapkan query dasar untuk mengambil indikator
        $query = Indikator::query();

        // 3. Terapkan logika hak akses dengan menambahkan kondisi 'where' jika perlu
        if ($user->role === 'opd') {
            // Jika pengguna adalah OPD, filter berdasarkan opd_id mereka
            $query->where('opd_id', $user->opd_id);
        }
        // Jika bukan OPD, tidak ada filter tambahan yang diterapkan,
        // sehingga semua indikator akan diambil.

        // 4. Eksekusi query dan kirim hasilnya ke view
        $indikators = $query->get();
        
        return view('dashboard', compact('indikators'));
    }
}