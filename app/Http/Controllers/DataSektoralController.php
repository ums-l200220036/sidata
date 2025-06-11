<?php

namespace App\Http\Controllers;

use App\Models\DataSektoral;
use App\Models\Indikator;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DataSektoralController extends Controller
{
    /**
     * Menampilkan data PENDIDIKAN berdasarkan jenis kelamin.
     */
    public function showPendidikanByGender($indikatorId, $tahun = null, $kecamatanId = null, $kelurahanId = null)
    {
        // 1. Ambil data yang sudah difilter dari helper
        $viewData = $this->getFilteredDataForView($indikatorId, $tahun, $kecamatanId, $kelurahanId);
        
        // 2. Jika data kosong, langsung kembalikan view kosong
        if ($viewData instanceof \Illuminate\View\View) {
            return $viewData;
        }

        // 3. Proses data dengan helper generik, beritahu bahwa kuncinya adalah 'pendidikan'
        $structuredData = $this->processGenderData($viewData['rawData'], 'pendidikan');
        
        // 4. Kirim semua data ke view yang tepat
        return view('tabel-pendidikan', array_merge($viewData, ['structuredData' => $structuredData]));
    }

    /**
     * Menampilkan data PEKERJAAN berdasarkan jenis kelamin.
     */
    public function showPekerjaanByGender($indikatorId, $tahun = null, $kecamatanId = null, $kelurahanId = null)
    {
        $viewData = $this->getFilteredDataForView($indikatorId, $tahun, $kecamatanId, $kelurahanId);
        if ($viewData instanceof \Illuminate\View\View) { return $viewData; }
        $structuredData = $this->processGenderData($viewData['rawData'], 'pekerjaan');
        return view('tabel-pekerjaan', array_merge($viewData, ['structuredData' => $structuredData]));
    }

    /**
     * Menampilkan data AGAMA berdasarkan jenis kelamin.
     */
    public function showAgamaByGender($indikatorId, $tahun = null, $kecamatanId = null, $kelurahanId = null)
    {
        $viewData = $this->getFilteredDataForView($indikatorId, $tahun, $kecamatanId, $kelurahanId);
        if ($viewData instanceof \Illuminate\View\View) { return $viewData; }
        $structuredData = $this->processGenderData($viewData['rawData'], 'agama');
        return view('tabel-agama', array_merge($viewData, ['structuredData' => $structuredData]));
    }

    // ===================================================================================
    // HELPER METHODS (Logika Inti yang Sudah Dirapikan)
    // ===================================================================================

    /**
     * Helper method UTAMA untuk mengambil dan memfilter semua data.
     * Logika yang berulang-ulang kita satukan di sini.
     */
    private function getFilteredDataForView($indikatorId, $tahun, $kecamatanId, $kelurahanId)
    {
        $indikator = Indikator::findOrFail($indikatorId);
        $availableYears = DataSektoral::where('indikator_id', $indikatorId)
            ->join('periode', 'data_sektoral.periode_id', '=', 'periode.id')
            ->distinct()->orderBy('periode.tahun', 'desc')->pluck('periode.tahun');

        if ($availableYears->isEmpty()) {
            return view('tabel-pendidikan-kosong', ['indikatorTitle' => $indikator->nama_indikator, 'message' => 'Belum ada data tersedia untuk indikator ini.']);
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

        // Kembalikan semua variabel yang dibutuhkan dalam bentuk array
        return [
            'rawData' => $rawData,
            'indikator' => $indikator,
            'indikatorTitle' => $indikator->nama_indikator,
            'tahunAnalisis' => $tahunAnalisis,
            'availableYears' => $availableYears,
            'kecamatans' => $kecamatans,
            'kelurahans' => $kelurahans,
            'selectedKecamatanId' => $kecamatanId,
            'selectedKelurahanId' => $kelurahanId,
        ];
    }

    /**
     * Helper method GENERIK untuk memproses data jenis kelamin.
     * Bisa digunakan untuk pendidikan, pekerjaan, agama, dll.
     */
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

            if ($item->satuan === 'Jiwa (L)') {
                $processedData[$kecamatan][$kelurahan][$dimensi]['L'] += (int)$item->nilai;
            } elseif ($item->satuan === 'Jiwa (P)') {
                $processedData[$kecamatan][$kelurahan][$dimensi]['P'] += (int)$item->nilai;
            }
        }

        $finalData = [];
        foreach ($processedData as $kecamatanName => $kelurahanList) {
            $kecamatanRowspan = 0;
            foreach ($kelurahanList as $kelurahanName => $dimensiList) {
                $kelurahanRowspan = count($dimensiList);
                $kecamatanRowspan += $kelurahanRowspan;
                
                foreach ($dimensiList as $dimensiName => $values) {
                    $jumlah = $values['L'] + $values['P'];
                    // Gunakan $dimensionKey sebagai kunci array dinamis
                    $finalData[$kecamatanName]['kelurahan'][$kelurahanName][$dimensionKey][$dimensiName] = [
                        'laki_n' => $values['L'],
                        'perempuan_n' => $values['P'],
                        'jumlah' => $jumlah,
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
}