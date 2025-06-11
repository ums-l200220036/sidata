<?php

namespace App\Http\Controllers;

use App\Models\DataSektoral;
use App\Models\Indikator;
use App\Models\Periode;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DataSektoralController extends Controller
{
    // ===================================================================================
    // METODE UNTUK LAPORAN FORMAT JENIS KELAMIN
    // ===================================================================================

    public function showPendidikanByGender($indikatorId, $tahun = null, $kecamatanId = null, $kelurahanId = null)
    {
        $viewData = $this->getFilteredDataForView($indikatorId, $tahun, $kecamatanId, $kelurahanId);
        if ($viewData instanceof \Illuminate\View\View) { return $viewData; }
        $structuredData = $this->processGenderData($viewData['rawData'], 'pendidikan');
        return view('tabel-gender', array_merge($viewData, ['structuredData' => $structuredData, 'dimensionKey' => 'pendidikan']));
    }

    public function showPekerjaanByGender($indikatorId, $tahun = null, $kecamatanId = null, $kelurahanId = null)
    {
        $viewData = $this->getFilteredDataForView($indikatorId, $tahun, $kecamatanId, $kelurahanId);
        if ($viewData instanceof \Illuminate\View\View) { return $viewData; }
        $structuredData = $this->processGenderData($viewData['rawData'], 'pekerjaan');
        return view('tabel-gender', array_merge($viewData, ['structuredData' => $structuredData, 'dimensionKey' => 'pekerjaan']));
    }

    public function showAgamaByGender($indikatorId, $tahun = null, $kecamatanId = null, $kelurahanId = null)
    {
        $viewData = $this->getFilteredDataForView($indikatorId, $tahun, $kecamatanId, $kelurahanId);
        if ($viewData instanceof \Illuminate\View\View) { return $viewData; }
        $structuredData = $this->processGenderData($viewData['rawData'], 'agama');
        return view('tabel-gender', array_merge($viewData, ['structuredData' => $structuredData, 'dimensionKey' => 'agama']));
    }

    // ===================================================================================
    // METODE UNTUK LAPORAN FORMAT PRIORITAS
    // ===================================================================================

    /**
     * Menampilkan laporan berbasis prioritas dengan filter lengkap.
     */
    public function showPrioritasReport($indikatorId, $tahun = null, $kecamatanId = null, $kelurahanId = null)
    {
        $indikator = \App\Models\Indikator::findOrFail($indikatorId);

        // Ambil daftar tahun yang tersedia
        $allYears = \App\Models\Periode::distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        if ($allYears->isEmpty()) {
            return view('tabel-kosong', ['indikatorTitle' => $indikator->nama_indikator, 'message' => 'Belum ada data tersedia.']);
        }
        $latestYear = $allYears->first();
        $tahunAnalisis = $tahun ?? $latestYear;
        $tahunSebelumnya = $tahunAnalisis - 1;

        // --- LOGIKA BARU: Ambil daftar wilayah untuk filter ---
        $kecamatans = \App\Models\Wilayah::whereNull('parent_id')->orderBy('kecamatan')->get();
        $kelurahans = collect();
        if ($kecamatanId) {
            $kelurahans = \App\Models\Wilayah::where('parent_id', $kecamatanId)->orderBy('kelurahan')->get();
        }

        // Ambil ID periode yang relevan
        $periodes = \App\Models\Periode::whereIn('tahun', [$tahunAnalisis, $tahunSebelumnya])->get();
        $periodeIds = $periodes->pluck('id');

        // Query data mentah
        $query = \App\Models\DataSektoral::where('indikator_id', $indikatorId)
            ->whereIn('periode_id', $periodeIds)
            ->with(['wilayah', 'dimensi', 'periode']);

        // --- LOGIKA BARU: Terapkan filter hak akses & pilihan dropdown ---
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'kecamatan') {
                $kecamatanId = $user->wilayah_id;
                $kelurahans = \App\Models\Wilayah::where('parent_id', $kecamatanId)->orderBy('kelurahan')->get();
            } elseif ($user->role === 'kelurahan') {
                $kelurahanId = $user->wilayah_id;
            }
        }
        
        if ($kelurahanId) {
            $query->where('wilayah_id', $kelurahanId);
        } elseif ($kecamatanId) {
            // Jika hanya kecamatan yang dipilih, ambil semua ID kelurahan di bawahnya
            $kelurahanAnakIds = \App\Models\Wilayah::where('parent_id', $kecamatanId)->pluck('id');
            $query->whereIn('wilayah_id', $kelurahanAnakIds);
        }
        // Jika tidak ada yang dipilih, tampilkan semua (untuk OPD)

        $rawData = $query->get();
        $structuredData = $this->processPrioritasData($rawData, $tahunAnalisis, $tahunSebelumnya);

        // Kirim semua variabel filter ke view
        return view('tabel-prioritas', [
            'indikatorTitle' => $indikator->nama_indikator,
            'dimensiHeader' => $indikator->dimensi_label ?? 'Prioritas',
            'structuredData' => $structuredData,
            'tahunAnalisis' => $tahunAnalisis,
            'tahunSebelumnya' => $tahunSebelumnya,
            'availableYears' => $allYears,
            'indikator' => $indikator,
            'kecamatans' => $kecamatans,
            'kelurahans' => $kelurahans,
            'selectedKecamatanId' => $kecamatanId,
            'selectedKelurahanId' => $kelurahanId,
        ]);
    }

    // ===================================================================================
    // HELPER METHODS
    // ===================================================================================

    private function getFilteredDataForView($indikatorId, $tahun, $kecamatanId, $kelurahanId)
    {
        $indikator = Indikator::findOrFail($indikatorId);
        $availableYears = DataSektoral::where('indikator_id', $indikatorId)
            ->join('periode', 'data_sektoral.periode_id', '=', 'periode.id')
            ->distinct()->orderBy('periode.tahun', 'desc')->pluck('periode.tahun');

        if ($availableYears->isEmpty()) {
            return view('tabel-kosong', ['indikatorTitle' => $indikator->nama_indikator, 'message' => 'Belum ada data tersedia.']);
        }
        
        $tahunAnalisis = $tahun && $availableYears->contains($tahun) ? $tahun : $availableYears->first();
        $kecamatans = Wilayah::whereNull('parent_id')->orderBy('kecamatan')->get();
        $kelurahans = collect();
        if ($kecamatanId) {
            $kelurahans = Wilayah::where('parent_id', $kecamatanId)->orderBy('kelurahan')->get();
        }

        $query = DataSektoral::where('indikator_id', $indikatorId)
                             ->whereIn('satuan', ['Jiwa (L)', 'Jiwa (P)'])
                             ->whereHas('periode', function ($q) use ($tahunAnalisis) { $q->where('tahun', $tahunAnalisis); })
                             ->with(['wilayah', 'dimensi']);
        
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'kecamatan') {
                $kecamatanId = $user->wilayah_id;
                $kelurahans = Wilayah::where('parent_id', $kecamatanId)->orderBy('kelurahan')->get();
            } elseif ($user->role === 'kelurahan') {
                $kelurahanId = $user->wilayah_id;
            }
        }

        if ($kelurahanId) {
            $query->where('wilayah_id', $kelurahanId);
        } elseif ($kecamatanId) {
            $kelurahanAnakIds = $kelurahans->pluck('id');
            $query->whereIn('wilayah_id', $kelurahanAnakIds);
        }

        $rawData = $query->orderBy('wilayah_id')->orderBy('dimensi_id')->get();

        return [
            'rawData' => $rawData, 'indikator' => $indikator,
            'indikatorTitle' => $indikator->nama_indikator,
            'dimensiHeader' => $indikator->dimensi_label ?? 'Dimensi',
            'tahunAnalisis' => $tahunAnalisis, 'availableYears' => $availableYears,
            'kecamatans' => $kecamatans, 'kelurahans' => $kelurahans,
            'selectedKecamatanId' => $kecamatanId, 'selectedKelurahanId' => $kelurahanId,
        ];
    }

    private function processGenderData($rawData, string $dimensionKey)
    {
        $processedData = [];
        foreach ($rawData as $item) {
            $kecamatan = $item->wilayah->kecamatan ?? 'Tidak Diketahui';
            $kelurahan = $item->wilayah->kelurahan ?? 'Tidak Diketahui';
            $dimensi = $item->dimensi->nama_dimensi ?? 'Tidak Diketahui';
            if (!isset($processedData[$kecamatan][$kelurahan][$dimensi])) {
                $processedData[$kecamatan][$kelurahan][$dimensi] = ['L' => 0, 'P' => 0];
            }
            if ($item->satuan === 'Jiwa (L)') { $processedData[$kecamatan][$kelurahan][$dimensi]['L'] += (int)$item->nilai; } 
            elseif ($item->satuan === 'Jiwa (P)') { $processedData[$kecamatan][$kelurahan][$dimensi]['P'] += (int)$item->nilai; }
        }

        $finalData = [];
        foreach ($processedData as $kecamatanName => $kelurahanList) {
            $kecamatanRowspan = 0;
            foreach ($kelurahanList as $kelurahanName => $dimensiList) {
                $kelurahanRowspan = count($dimensiList);
                $kecamatanRowspan += $kelurahanRowspan;
                foreach ($dimensiList as $dimensiName => $values) {
                    $jumlah = $values['L'] + $values['P'];
                    $finalData[$kecamatanName]['kelurahan'][$kelurahanName][$dimensionKey][$dimensiName] = [
                        'laki_n' => $values['L'], 'perempuan_n' => $values['P'], 'jumlah' => $jumlah,
                        'laki_pct' => ($jumlah > 0) ? ($values['L'] / $jumlah) * 100 : 0,
                        'perempuan_pct' => ($jumlah > 0) ? ($values['P'] / $jumlah) * 100 : 0,
                    ];
                }
                 $finalData[$kecamatanName]['kelurahan'][$kelurahanName]['rowspan'] = $kelurahanRowspan;
            }
            $finalData[$kecamatanName]['rowspan'] = $kecamatanRowspan;
        }
        return $finalData;
    }

    /**
     * Helper method GENERIK untuk memproses data berbasis prioritas.
     * VERSI FINAL YANG SUDAH DIPERBAIKI.
     */
    private function processPrioritasData($rawData, $tahunN, $tahunN1)
    {
        $processed = [];
        // Tahap 1: Pivoting data mentah (Bagian ini sudah benar)
        foreach($rawData as $item) {
            if (empty($item->wilayah) || empty($item->dimensi) || empty($item->periode)) continue;
            
            $kec = $item->wilayah->kecamatan;
            $kel = $item->wilayah->kelurahan;
            $prioritas = $item->dimensi->nama_dimensi;
            $tahun = $item->periode->tahun;
            $semester = $item->periode->semester;
            $satuan = strtolower($item->satuan);
            $nilai = (int)$item->nilai;

            if (!isset($processed[$kec][$kel][$prioritas])) {
                $processed[$kec][$kel][$prioritas] = [
                    "{$tahunN1}_s1" => ['individu' => 0, 'keluarga' => 0],
                    "{$tahunN1}_s2" => ['individu' => 0, 'keluarga' => 0],
                    "{$tahunN}_s1"  => ['individu' => 0, 'keluarga' => 0],
                ];
            }
            if ($tahun == $tahunN1 && $semester == 1) $processed[$kec][$kel][$prioritas]["{$tahunN1}_s1"][$satuan] += $nilai;
            if ($tahun == $tahunN1 && $semester == 2) $processed[$kec][$kel][$prioritas]["{$tahunN1}_s2"][$satuan] += $nilai;
            if ($tahun == $tahunN  && $semester == 1) $processed[$kec][$kel][$prioritas]["{$tahunN}_s1"][$satuan]  += $nilai;
        }

        // --- PERBAIKAN LOGIKA DI SINI ---
        // Tahap 2: Strukturkan data final dengan benar
        $finalData = [];
        foreach ($processed as $kecamatanName => $kelurahanList) {
            $kecamatanRowspan = 0;
            $kelurahanDataForView = []; // Buat array sementara untuk data kelurahan

            foreach ($kelurahanList as $kelurahanName => $prioritasList) {
                $kelurahanRowspan = count($prioritasList) + 1; // +1 untuk baris Total
                $kecamatanRowspan += $kelurahanRowspan;

                $totalKelurahan = [
                    "{$tahunN1}_s1" => ['individu' => 0, 'keluarga' => 0],
                    "{$tahunN1}_s2" => ['individu' => 0, 'keluarga' => 0],
                    "{$tahunN}_s1"  => ['individu' => 0, 'keluarga' => 0],
                ];

                foreach($prioritasList as $prioritasName => $values) {
                    // Akumulasikan nilai ke total
                    $totalKelurahan["{$tahunN1}_s1"]['individu'] += $values["{$tahunN1}_s1"]['individu'];
                    $totalKelurahan["{$tahunN1}_s1"]['keluarga'] += $values["{$tahunN1}_s1"]['keluarga'];
                    $totalKelurahan["{$tahunN1}_s2"]['individu'] += $values["{$tahunN1}_s2"]['individu'];
                    $totalKelurahan["{$tahunN1}_s2"]['keluarga'] += $values["{$tahunN1}_s2"]['keluarga'];
                    $totalKelurahan["{$tahunN}_s1"]['individu']  += $values["{$tahunN}_s1"]['individu'];
                    $totalKelurahan["{$tahunN}_s1"]['keluarga']  += $values["{$tahunN}_s1"]['keluarga'];
                }
                
                // Susun data untuk satu kelurahan
                $kelurahanDataForView[$kelurahanName] = [
                    'rowspan' => $kelurahanRowspan,
                    'prioritas' => $prioritasList,
                    'total' => $totalKelurahan
                ];
            }
            
            // Susun data final untuk satu kecamatan
            $finalData[$kecamatanName] = [
                'rowspan' => $kecamatanRowspan,
                'kelurahan' => $kelurahanDataForView // Letakkan data kelurahan di dalam key 'kelurahan'
            ];
        }
        
        return $finalData;
    }
}