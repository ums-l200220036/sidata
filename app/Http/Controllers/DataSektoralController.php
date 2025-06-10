<?php

namespace App\Http\Controllers;

use App\Models\DataSektoral;
use App\Models\Indikator; // Jika Anda perlu mengambil nama indikator
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
        // Mendapatkan nama indikator untuk judul (opsional)
        $indikator = Indikator::find($indikatorId);
        $indikatorTitle = $indikator ? $indikator->nama_indikator : 'Data Sektoral';

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
                    'rowspan' => 0, // Akan dihitung nanti
                    'kelurahan' => [],
                ];
            }

            // Inisialisasi struktur untuk kelurahan di bawah kecamatan
            if (!isset($structuredData[$kecamatan]['kelurahan'][$kelurahan])) {
                $structuredData[$kecamatan]['kelurahan'][$kelurahan] = [
                    'rowspan' => 0, // Akan dihitung nanti
                    'dimensi' => [],
                ];
            }

            // Inisialisasi struktur untuk dimensi (Agama) di bawah kelurahan
            if (!isset($structuredData[$kecamatan]['kelurahan'][$kelurahan]['dimensi'][$dimensiNama])) {
                // Inisialisasi nilai untuk semua tahun target dengan null atau 0
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
                // Rowspan kelurahan = jumlah dimensi (agama) di dalamnya
                $kelurahanData['rowspan'] = count($kelurahanData['dimensi']);
                // Tambahkan ke rowspan kecamatan
                $kecamatanData['rowspan'] += $kelurahanData['rowspan'];
            }
        }
        // Pastikan untuk unset referensi setelah loop selesai
        unset($kecamatanData, $kelurahanData);


        // Debugging (opsional)
        // Log::info('Structured Data:', $structuredData);

        return view('tabel', [ // Ganti 'data.sektoral_table' dengan path view Anda
            'indikatorTitle' => $indikatorTitle,
            'structuredData' => $structuredData,
            'targetYears' => $targetYears,
        ]);
    }
}