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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataSektoralExport;
use Illuminate\Support\Str;
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

    public function exportPublicReport(Request $request, $indikatorId = null)
    {
        // Panggil method yang sudah ada untuk mendapatkan semua data yang telah diproses
        // Ini memastikan data yang diekspor 100% sama dengan yang ditampilkan
        $viewOrData = $this->showPublicReport($request, $indikatorId);

        // Periksa apakah yang dikembalikan adalah objek View dan namanya adalah 'tabel-kosong'
        if ($viewOrData instanceof \Illuminate\View\View && $viewOrData->getName() === 'tabel-kosong') {
             return back()->with('error', 'Tidak ada data untuk diekspor.');
        }

        // Ekstrak data dari objek view untuk dikirim ke file Excel
        $dataForExport = $viewOrData->getData();

        // Ambil nama indikator dari data untuk nama file
        $selectedIndicator = $dataForExport['availableIndicators']->find($dataForExport['selectedIndicatorId']);
        $indikatorTitle = $selectedIndicator->nama_indikator ?? 'Laporan Publik';
        $fileName = Str::slug($indikatorTitle) . '-' . date('Y-m-d') . '.xlsx';
        
        // Panggil class export dengan view khusus untuk laporan publik
        return Excel::download(new DataSektoralExport('exports.publik', $dataForExport), $fileName);
    }
}