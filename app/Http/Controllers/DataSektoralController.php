<?php

namespace App\Http\Controllers;

use App\Models\DataSektoral;
use App\Models\Indikator; // Pastikan Anda mengimpor model Indikator
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Untuk debugging

class DataSektoralController extends Controller
{
    /**
     * Menampilkan data sektoral dalam format tabel hierarkis untuk indikator tertentu.
     *
     * @param int $indikatorId ID Indikator yang akan ditampilkan datanya.
     * @return \Illuminate\View\View
     */
    public function showIndicatorData($indikatorId)
    {
        // Mendapatkan objek Indikator
        $indikator = Indikator::find($indikatorId);

        // Mendapatkan nama indikator untuk judul halaman
        $indikatorTitle = $indikator ? $indikator->nama_indikator : 'Data Sektoral';

        // --- PERBAIKAN DI SINI ---
        // Ambil label dimensi dari kolom 'dimensi_label' di tabel indikator
        // Jika kolomnya kosong atau indikator tidak ditemukan, gunakan 'Dimensi' sebagai default
        $dimensiHeader = $indikator ? ($indikator->dimensi_label ?? 'Dimensi') : 'Dimensi';
        // --- AKHIR PERBAIKAN ---

        // Tahun yang ingin ditampilkan di header tabel
        $targetYears = [2021, 2022, 2023, 2024]; // Sesuaikan tahun yang Anda inginkan

        // Ambil data sektoral berdasarkan indikator_id
        // Eager load relasi yang dibutuhkan (wilayah, periode, dimensi)
        $rawData = DataSektoral::where('indikator_id', $indikatorId)
                               ->with(['wilayah', 'periode', 'dimensi'])
                               ->orderBy('wilayah_id')
                               ->orderBy('dimensi_id')
                               ->orderBy('periode_id')
                               ->get();

        $structuredData = [];

        // Proses rawData menjadi struktur hierarkis yang sesuai untuk tabel
        foreach ($rawData as $item) {
            $kecamatan = $item->wilayah->kecamatan ?? 'Tidak Diketahui';
            $kelurahan = $item->wilayah->kelurahan ?? 'Tidak Diketahui';
            $dimensiNama = $item->dimensi->nama_dimensi ?? 'Tidak Diketahui';
            $tahun = $item->periode->tahun ?? null;
            $nilai = $item->nilai;

            // Inisialisasi struktur untuk kecamatan
            if (!isset($structuredData[$kecamatan])) {
                $structuredData[$kecamatan] = [
                    'rowspan' => 0,
                    'kelurahan' => [],
                ];
            }

            // Inisialisasi struktur untuk kelurahan di bawah kecamatan
            if (!isset($structuredData[$kecamatan]['kelurahan'][$kelurahan])) {
                $structuredData[$kecamatan]['kelurahan'][$kelurahan] = [
                    'rowspan' => 0,
                    'dimensi' => [],
                ];
            }

            // Inisialisasi struktur untuk dimensi di bawah kelurahan
            if (!isset($structuredData[$kecamatan]['kelurahan'][$kelurahan]['dimensi'][$dimensiNama])) {
                $structuredData[$kecamatan]['kelurahan'][$kelurahan]['dimensi'][$dimensiNama] = array_fill_keys($targetYears, null);
            }

            // Masukkan nilai jika tahunnya ada dalam targetYears
            if ($tahun && in_array($tahun, $targetYears)) {
                $structuredData[$kecamatan]['kelurahan'][$kelurahan]['dimensi'][$dimensiNama][$tahun] = $nilai;
            }
        }

        // Hitung rowspan untuk setiap kelurahan dan kecamatan
        foreach ($structuredData as $kecamatanName => &$kecamatanData) {
            foreach ($kecamatanData['kelurahan'] as $kelurahanName => &$kelurahanData) {
                $kelurahanData['rowspan'] = count($kelurahanData['dimensi']);
                $kecamatanData['rowspan'] += $kelurahanData['rowspan'];
            }
        }
        // Pastikan untuk unset referensi setelah loop selesai
        unset($kecamatanData, $kelurahanData);

        // Debugging (opsional)
        // Log::info('Structured Data:', $structuredData);

        return view('tabel', [ // Pastikan 'tabel' adalah nama view Anda yang benar
            'indikatorTitle' => $indikatorTitle,
            'structuredData' => $structuredData,
            'targetYears' => $targetYears,
            'dimensiHeader' => $dimensiHeader, // <-- INI YANG DITAMBAHKAN
        ]);
    }
}