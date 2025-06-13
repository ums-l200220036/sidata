<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use App\Models\Opd;
use App\Models\Periode;
use App\Models\DataSektoral;
use App\Models\Dimensi;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan daftar indikator
     * yang sudah difilter berdasarkan hak akses pengguna.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Indikator::query();

        if ($user->role === 'opd') {
            $query->where('opd_id', $user->opd_id);
        }

        $indikators = $query->get();
        return view('dashboard', compact('indikators'));
    }

    /**
     * Menampilkan halaman laporan publik yang dinamis (generik).
     * (Metode ini tidak diubah, hanya ditambahkan di sini untuk kelengkapan)
     */
    public function showPublicReport(Request $request, $indikatorId = null)
    {
        $availableIndicators = Indikator::orderBy('nama_indikator')->get();
        $kecamatans = Wilayah::whereNull('parent_id')->orderBy('kecamatan')->get();
        $selectedKecamatanId = $request->query('kecamatan', 0);
        $selectedKelurahanId = $request->query('kelurahan', 0);
        $kelurahans = $selectedKecamatanId ? Wilayah::where('parent_id', $selectedKecamatanId)->orderBy('kelurahan')->get() : collect();
        $selectedIndicatorId = $indikatorId ?? $availableIndicators->first()->id;
        $selectedIndicator = $availableIndicators->find($selectedIndicatorId);
        $targetYears = range(date('Y') - 3, date('Y'));
        $periodeIds = Periode::whereIn('tahun', $targetYears)->pluck('id');

        $query = DataSektoral::where('indikator_id', $selectedIndicatorId)
                             ->whereIn('periode_id', $periodeIds)
                             ->with(['wilayah', 'dimensi', 'periode']);

        if ($selectedKelurahanId) {
            $query->where('wilayah_id', $selectedKelurahanId);
        } elseif ($selectedKecamatanId) {
            $kelurahanAnakIds = Wilayah::where('parent_id', $selectedKecamatanId)->pluck('id');
            $query->whereIn('wilayah_id', $kelurahanAnakIds);
        }
        $rawData = $query->get();

        $structuredData = $this->processPublicData($rawData, $targetYears);

        return view('tabel-publik', [
            'structuredData' => $structuredData,
            'years' => $targetYears,
            'dimensiHeader' => $selectedIndicator->dimensi_label ?? 'Dimensi',
            'availableIndicators' => $availableIndicators,
            'selectedIndicatorId' => (int)$selectedIndicatorId,
            'kecamatans' => $kecamatans,
            'kelurahans' => $kelurahans,
            'selectedKecamatanId' => (int)$selectedKecamatanId,
            'selectedKelurahanId' => (int)$selectedKelurahanId,
        ]);
    }

    private function processPublicData($rawData, $targetYears)
    {
        $pivotedData = [];
        foreach ($rawData as $item) {
            if(!$item->wilayah || !$item->dimensi || !$item->periode) continue;

            $kecamatan = $item->wilayah->kecamatan;
            $kelurahan = $item->wilayah->kelurahan;
            $dimensi = $item->dimensi->nama_dimensi;
            $tahun = $item->periode->tahun;
            $nilai = $item->nilai;

            $pivotedData[$kecamatan][$kelurahan][$dimensi][$tahun] = $nilai;
        }

        $structuredData = [];
        foreach ($pivotedData as $kecamatanName => $kelurahanItems) {
            $kecamatanRowspan = 0;
            $kelurahanDataForView = [];

            foreach ($kelurahanItems as $kelurahanName => $dimensiItems) {
                $kelurahanRowspan = count($dimensiItems);
                $kecamatanRowspan += $kelurahanRowspan;

                $dimensiDataForView = [];
                foreach($dimensiItems as $dimensiName => $yearlyValues) {
                    $valuesForYears = [];
                    foreach ($targetYears as $year) {
                        $valuesForYears[] = $yearlyValues[$year] ?? 'N/A';
                    }
                    $dimensiDataForView[$dimensiName] = $valuesForYears;
                }

                $kelurahanDataForView[$kelurahanName] = [
                    'dimensi' => $dimensiDataForView,
                    'rowspan' => $kelurahanRowspan
                ];
            }

            $structuredData[$kecamatanName] = [
                'kelurahan' => $kelurahanDataForView,
                'rowspan' => $kecamatanRowspan
            ];
        }
        return $structuredData;
    }

    // Di dalam file app/Http/Controllers/DataSektoralController.php

    /**
     * Menampilkan laporan Pegawai per Usia dengan struktur dan kalkulasi final.
     *
     * @param Request $request
     * @param int $indikatorId
     * @return \Illuminate\View\View
     */
    public function showPegawaiUsiaReport(Request $request, $indikatorId)
    {
        $indikator = Indikator::findOrFail($indikatorId);

        // Filter dari URL Query, defaultnya null (tampilkan semua)
        $selectedKecamatan = $request->input('kecamatan');
        $selectedKelurahan = $request->input('kelurahan');

        // Ambil data untuk dropdown filter
        $kecamatans = Wilayah::select('kecamatan')->distinct()->orderBy('kecamatan')->get();
        // Ambil kelurahan HANYA jika kecamatan sudah dipilih, untuk cascading dropdown
        $kelurahans = $selectedKecamatan ? Wilayah::select('kelurahan')->distinct()->where('kecamatan', $selectedKecamatan)->orderBy('kelurahan')->get() : collect();

        // Ambil 2 tahun terakhir yang memiliki data untuk indikator ini
        $uniqueYearsInView = Periode::whereHas('dataSektoral', function($q) use ($indikatorId) {
            $q->where('indikator_id', $indikatorId);
        })->select('tahun')->distinct()->orderBy('tahun', 'desc')->limit(2)->pluck('tahun')->sort();
        
        // Handle jika tidak ada data sama sekali
        if ($uniqueYearsInView->isEmpty()) {
            return view('tabel-kosong', ['indikator' => $indikator, 'message' => 'Data untuk laporan ini belum tersedia.']);
        }

        // Bangun query dasar
        $query = DataSektoral::with(['wilayah', 'dimensi', 'periode'])
            ->where('indikator_id', $indikator->id)
            ->whereIn('satuan', ['ASN', 'Non ASN']);

        // Terapkan filter ke query
        if ($selectedKecamatan) {
            $query->whereHas('wilayah', function($q) use ($selectedKecamatan) {
                $q->where('kecamatan', $selectedKecamatan);
            });
        }
        if ($selectedKelurahan) {
            $query->whereHas('wilayah', function($q) use ($selectedKelurahan) {
                $q->where('kelurahan', $selectedKelurahan);
            });
        }

        $rawData = $query->get();

        // Panggil helper yang sudah disempurnakan
        $processed = $this->processPegawaiUsiaData($rawData, $uniqueYearsInView);

        return view('tabel-bkpsdm', [
            'indikator' => $indikator,
            'structuredData' => $processed['data'],
            'grandTotalsPerYear' => $processed['totals'],
            'uniqueYearsInView' => $uniqueYearsInView,
            'kecamatans' => $kecamatans,
            'kelurahans' => $kelurahans,
            'selectedKecamatan' => $selectedKecamatan,
            'selectedKelurahan' => $selectedKelurahan,
        ]);
    }

    /**
     * Helper method final untuk memproses data Pegawai per Usia.
     * VERSI BARU: dengan struktur data yang benar untuk view.
     */
    private function processPegawaiUsiaData($rawData, $targetYears)
    {
        $pivotedData = [];
        $grandTotals = [];

        // Inisialisasi grand total
        foreach ($targetYears as $year) {
            $grandTotals[$year] = ['ASN' => 0, 'Non ASN' => 0, 'Total' => 0];
        }

        // Tahap 1: Pivot data mentah (tidak ada perubahan)
        foreach ($rawData as $item) {
            if (!$item->wilayah || !$item->dimensi || !$item->periode) continue;
            
            $kec = $item->wilayah->kecamatan;
            $kel = $item->wilayah->kelurahan;
            $parts = explode(' - ', $item->dimensi->nama_dimensi);
            $usia = trim($parts[0]);
            $jk = trim($parts[1]);
            $tahun = $item->periode->tahun;
            $status = $item->satuan; // ASN atau Non ASN
            $nilai = (int)$item->nilai;

            if (!isset($pivotedData[$kec][$kel][$usia][$jk][$tahun])) {
                $pivotedData[$kec][$kel][$usia][$jk][$tahun] = ['ASN' => 0, 'Non ASN' => 0];
            }
            $pivotedData[$kec][$kel][$usia][$jk][$tahun][$status] += $nilai;
        }

        // --- PERBAIKAN UTAMA DI SINI ---
        // Tahap 2: Strukturkan data, hitung total, dan hitung semua rowspan dengan benar
        $structuredData = [];
        foreach ($pivotedData as $kecamatanName => $kelurahanList) {
            $kecamatanRowspan = 0;
            $kelurahanDataForView = []; // Array sementara untuk menampung kelurahan

            foreach ($kelurahanList as $kelurahanName => $usiaList) {
                $kelurahanRowspan = 0;
                $usiaDataForView = []; // Array sementara untuk menampung data usia

                foreach ($usiaList as $usiaName => $jenisKelaminList) {
                    $usiaRowspan = count($jenisKelaminList);
                    $kelurahanRowspan += $usiaRowspan;
                    
                    $jkRows = [];
                    foreach ($jenisKelaminList as $jkName => $yearlyData) {
                        $row = ['jenis_kelamin' => $jkName, 'yearly_data' => []];
                        foreach ($targetYears as $year) {
                            $asn = $yearlyData[$year]['ASN'] ?? 0;
                            $nonAsn = $yearlyData[$year]['Non ASN'] ?? 0;
                            $total = $asn + $nonAsn;

                            $row['yearly_data'][$year] = ['ASN' => $asn, 'Non ASN' => $nonAsn, 'Total' => $total];
                            
                            $grandTotals[$year]['ASN'] += $asn;
                            $grandTotals[$year]['Non ASN'] += $nonAsn;
                            $grandTotals[$year]['Total'] += $total;
                        }
                        $jkRows[] = $row;
                    }
                    $usiaDataForView[$usiaName] = [
                        'rowspan_usia' => $usiaRowspan,
                        'rows' => $jkRows
                    ];
                }
                $kelurahanDataForView[$kelurahanName] = [
                    'rowspan_kelurahan' => $kelurahanRowspan,
                    'usias' => $usiaDataForView
                ];
                $kecamatanRowspan += $kelurahanRowspan;
            }
            $structuredData[$kecamatanName] = [
                'rowspan_kecamatan' => $kecamatanRowspan,
                'kelurahans' => $kelurahanDataForView // Letakkan data kelurahan di dalam key 'kelurahans'
            ];
        }

        return ['data' => $structuredData, 'totals' => $grandTotals];
    }
}