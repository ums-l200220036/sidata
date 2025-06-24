<?php

namespace App\Http\Controllers;

use App\Models\DataSektoral;
use App\Models\Indikator;
use App\Models\Periode;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataSektoralExport;
use Illuminate\Support\Str;
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

    public function showPegawaiUsiaReport(Request $request, $indikatorId)
    {
        $indikator = Indikator::findOrFail($indikatorId);
        $user = Auth::user();

        // Ambil filter dari request
        $selectedKecamatan = $request->input('kecamatan');
        $selectedKelurahan = $request->input('kelurahan');

        // Ambil data untuk dropdown filter
        $allKecamatans = Wilayah::whereNull('parent_id')->orderBy('kecamatan')->get();
        $kelurahans = collect();

        // Bangun query dasar
        $query = DataSektoral::where('indikator_id', $indikatorId)
                            ->with(['wilayah.parent', 'dimensi', 'periode']);

        // =======================================================================
        // TAHAP 1: KUNCI DATA BERDASARKAN HAK AKSES
        // =======================================================================
        if ($user->role === 'kecamatan') {
            $kecamatanWilayah = Wilayah::find($user->wilayah_id);
            if ($kecamatanWilayah) {
                $selectedKecamatan = $kecamatanWilayah->kecamatan; // Kunci nama kecamatan
                $query->whereHas('wilayah', fn($q) => $q->where('kecamatan', $selectedKecamatan));
                $kelurahans = Wilayah::where('parent_id', $user->wilayah_id)->orderBy('kelurahan')->get();
            }
        } elseif ($user->role === 'kelurahan') {
            $query->where('wilayah_id', $user->wilayah_id);
            $kelurahanWilayah = Wilayah::with('parent')->find($user->wilayah_id);
            if ($kelurahanWilayah) {
                $selectedKelurahan = $kelurahanWilayah->kelurahan;
                $selectedKecamatan = $kelurahanWilayah->parent->kecamatan ?? null;
            }
        }
        // =======================================================================
        
        // =======================================================================
        // TAHAP 2: TERAPKAN FILTER DARI DROPDOWN (LOGIKA DIPERBAIKI)
        // =======================================================================
        // Jika kelurahan dipilih (bisa oleh OPD atau Kecamatan), ini adalah filter paling spesifik
        if ($selectedKelurahan) {
            $query->whereHas('wilayah', fn($q) => $q->where('kelurahan', $selectedKelurahan));
        } 
        // Jika tidak ada kelurahan dipilih, dan kecamatan dipilih (hanya bisa oleh OPD, karena kecamatan sudah dikunci untuk role kecamatan)
        elseif ($selectedKecamatan && $user->role === 'opd') {
            $query->whereHas('wilayah', fn($q) => $q->where('kecamatan', $selectedKecamatan));
        }
        // =======================================================================

        // Logika untuk mengisi dropdown kelurahan jika OPD memilih kecamatan
        if ($user->role === 'opd' && $selectedKecamatan && $kelurahans->isEmpty()) {
            $kecId = $allKecamatans->firstWhere('kecamatan', $selectedKecamatan)->id ?? null;
            if ($kecId) {
                $kelurahans = Wilayah::where('parent_id', $kecId)->orderBy('kelurahan')->get();
            }
        }

        $rawData = $query->get();

        if ($rawData->isEmpty()) {
            return view('tabel-kosong', [
                'indikator' => $indikator, 
                'indikatorTitle' => $indikator->nama_indikator, 
                'message' => 'Data untuk filter yang dipilih tidak ditemukan.'
            ]);
        }

        $uniqueYearsInView = $rawData->pluck('periode.tahun')->unique()->filter()->sort()->values();
        $processed = $this->processPegawaiUsiaData($rawData, $uniqueYearsInView);

        return view('tabel-bkpsdm', [
            'indikator' => $indikator,
            'indikatorTitle' => $indikator->nama_indikator,
            'structuredData' => $processed['data'],
            'grandTotalsPerYear' => $processed['totals'],
            'uniqueYearsInView' => $uniqueYearsInView,
            'kecamatans' => $allKecamatans,
            'kelurahans' => $kelurahans,
            'selectedKecamatan' => $selectedKecamatan,
            'selectedKelurahan' => $selectedKelurahan,
        ]);
    }

    /**
     * Helper method final untuk memproses data Pegawai per Usia.
     */
    private function processPegawaiUsiaData($rawData, $targetYears)
    {
        $pivotedData = [];
        $grandTotals = [];

        // Inisialisasi grand total
        foreach ($targetYears as $year) {
            $grandTotals[$year] = ['ASN' => 0, 'Non ASN' => 0, 'Total' => 0];
        }

        // Tahap 1: Pivot data mentah
        foreach ($rawData as $item) {
            if (!$item->wilayah || !$item->dimensi || !$item->periode || !$targetYears->contains($item->periode->tahun)) continue;
            
            $kec = $item->wilayah->parent->kecamatan ?? $item->wilayah->kecamatan ?? 'Lainnya';
            $kel = $item->wilayah->kelurahan;
            
            $parts = explode(' - ', $item->dimensi->nama_dimensi);
            $usia = trim($parts[0] ?? 'N/A');
            $jk = trim($parts[1] ?? 'N/A');
            
            $tahun = $item->periode->tahun;
            $status = $item->satuan;
            $nilai = (int) $item->nilai;

            if (!isset($pivotedData[$kec][$kel][$usia][$jk][$tahun])) {
                $pivotedData[$kec][$kel][$usia][$jk][$tahun] = ['ASN' => 0, 'Non ASN' => 0];
            }
            $pivotedData[$kec][$kel][$usia][$jk][$tahun][$status] += $nilai;
        }

        // ==========================================================
        // TAHAP 2: PERBAIKAN LOGIKA ROWSPAN DAN STRUKTUR DATA
        // ==========================================================
        $structuredData = [];
        foreach ($pivotedData as $kecamatanName => $kelurahanList) {
            $kecamatanRowspan = 0;
            
            foreach ($kelurahanList as $kelurahanName => $usiaList) {
                $kelurahanRowspan = 0;
                
                foreach ($usiaList as $usiaName => $jenisKelaminList) {
                    // Setiap kategori usia (misal: 21-30 Tahun) akan berisi beberapa baris (Laki-laki/Perempuan)
                    $kelurahanRowspan += count($jenisKelaminList);
                }
                $kecamatanRowspan += $kelurahanRowspan;
            }

            // Setelah semua rowspan dihitung, kita bangun strukturnya
            $kelurahanDataForView = [];
            foreach ($kelurahanList as $kelurahanName => $usiaList) {
                $kelurahanRowspanCurrent = 0; // Hitung lagi untuk kelurahan spesifik ini
                $usiaDataForView = [];
                
                foreach($usiaList as $usiaName => $jenisKelaminList) {
                    $usiaRowspan = count($jenisKelaminList);
                    $kelurahanRowspanCurrent += $usiaRowspan;
                    
                    $jkRows = [];
                    foreach($jenisKelaminList as $jkName => $yearlyData) {
                        $row = ['jenis_kelamin' => $jkName, 'yearly_data' => []];
                        foreach ($targetYears as $year) {
                            $asn = $yearlyData[$year]['ASN'] ?? 0;
                            $nonAsn = $yearlyData[$year]['Non ASN'] ?? 0;
                            $total = $asn + $nonAsn;
                            
                            $row['yearly_data'][$year] = ['ASN' => $asn, 'Non ASN' => $nonAsn, 'Total' => $total];
                            
                            // Cek untuk grand total hanya jika jkRows belum dihitung untuk tahun ini
                            if(!isset($jkRows[0]['yearly_data'][$year])){
                                $grandTotals[$year]['ASN'] += $asn;
                                $grandTotals[$year]['Non ASN'] += $nonAsn;
                                $grandTotals[$year]['Total'] += $total;
                            }
                        }
                        $jkRows[] = $row;
                    }
                    $usiaDataForView[$usiaName] = [
                        'rowspan_usia' => $usiaRowspan,
                        'rows' => $jkRows,
                    ];
                }
                $kelurahanDataForView[$kelurahanName] = [
                    'rowspan_kelurahan' => $kelurahanRowspanCurrent,
                    'usias' => $usiaDataForView,
                ];
            }

            $structuredData[$kecamatanName] = [
                'rowspan_kecamatan' => $kecamatanRowspan,
                'kelurahans' => $kelurahanDataForView,
            ];
        }
        
        return ['data' => $structuredData, 'totals' => $grandTotals];
    }

    // ===================================================================================
    // METODE UNTUK EXPORT KE EXCEL
    // ===================================================================================
    
    /**
     * Menangani permintaan ekspor untuk laporan Pegawai per Usia.
     */
    public function exportPegawaiUsiaReport(Request $request, $indikatorId)
    {
        // Panggil method yang sudah ada untuk mendapatkan semua data yang telah diproses
        $viewOrData = $this->showPegawaiUsiaReport($request, $indikatorId);

        // === BAGIAN YANG DIPERBAIKI ===
        // Periksa apakah yang dikembalikan adalah objek View dan namanya adalah 'tabel-kosong'
        if ($viewOrData instanceof \Illuminate\View\View && $viewOrData->getName() === 'tabel-kosong') {
            return back()->with('error', 'Tidak ada data untuk diekspor.');
        }

        // Jika bukan view 'tabel-kosong', berarti itu adalah view dengan data
        // Ekstrak data dari objek view untuk dikirim ke file Excel
        $dataForExport = $viewOrData->getData();

        $indikatorTitle = $dataForExport['indikator']->nama_indikator ?? 'Laporan Pegawai Usia';
        $fileName = Str::slug($indikatorTitle) . '-' . date('Y-m-d') . '.xlsx';
        
        // Panggil class export dengan view khusus untuk bkpsdm
        // Pastikan Anda sudah membuat file 'resources/views/exports/bkpsdm.blade.php'
        return Excel::download(new DataSektoralExport('exports.bkpsdm', $dataForExport), $fileName);
    }

    public function exportPrioritas($indikatorId, $tahun = null, $kecamatanId = null, $kelurahanId = null)
    {
        // Panggil metode showPrioritasReport untuk mendapatkan semua datanya
        $view = $this->showPrioritasReport($indikatorId, $tahun, $kecamatanId, $kelurahanId);

        // Ekstrak data dari view object
        $dataForExport = $view->getData();
        
        $indikatorTitle = $dataForExport['indikatorTitle'] ?? 'Laporan Prioritas';
        $fileName = Str::slug($indikatorTitle) . '-' . date('Y-m-d') . '.xlsx';

        return Excel::download(new DataSektoralExport('exports.prioritas', $dataForExport), $fileName);
    }
    
    public function exportPendidikanByGender($indikatorId, $tahun = null, $kecamatanId = null, $kelurahanId = null)
    {
        return $this->exportGender('pendidikan', $indikatorId, $tahun, $kecamatanId, $kelurahanId);
    }

    public function exportPekerjaanByGender($indikatorId, $tahun = null, $kecamatanId = null, $kelurahanId = null)
    {
        return $this->exportGender('pekerjaan', $indikatorId, $tahun, $kecamatanId, $kelurahanId);
    }
    
    public function exportAgamaByGender($indikatorId, $tahun = null, $kecamatanId = null, $kelurahanId = null)
    {
        return $this->exportGender('agama', $indikatorId, $tahun, $kecamatanId, $kelurahanId);
    }

    /**
     * Helper method generik untuk ekspor laporan gender.
     */
    private function exportGender($dimensionKey, $indikatorId, $tahun, $kecamatanId, $kelurahanId)
    {
        // Panggil helper utama untuk mengambil dan memfilter data
        $viewData = $this->getFilteredDataForView($indikatorId, $tahun, $kecamatanId, $kelurahanId);

        // Jika data kosong, kembalikan pesan
        if ($viewData instanceof \Illuminate\View\View) {
            return back()->with('error', 'Tidak ada data untuk diekspor.');
        }

        // Proses data dan tambahkan kunci dimensi
        $structuredData = $this->processGenderData($viewData['rawData'], $dimensionKey);
        $dataForExport = array_merge($viewData, ['structuredData' => $structuredData, 'dimensionKey' => $dimensionKey]);
        
        $indikatorTitle = $dataForExport['indikatorTitle'] ?? 'Laporan Gender';
        $fileName = Str::slug($indikatorTitle) . '-' . date('Y-m-d') . '.xlsx';

        return Excel::download(new DataSektoralExport('exports.gender', $dataForExport), $fileName);
    }
}