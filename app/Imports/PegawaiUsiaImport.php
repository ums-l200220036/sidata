<?php
// File: app/Imports/PegawaiUsiaImport.php

namespace App\Imports;

use App\Models\Dimensi;
use App\Models\DataSektoral;
use App\Models\Wilayah;
use App\Models\Periode;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PegawaiUsiaImport implements ToCollection
{
    protected $indikatorId;
    protected $indikatorOpdId;

    public function __construct($indikatorId, $indikatorOpdId)
    {
        $this->indikatorId = $indikatorId;
        $this->indikatorOpdId = $indikatorOpdId;
    }

    protected function fillForward(array $row): array
    {
        $lastValue = null;
        foreach ($row as $index => $value) {
            $cleanedValue = str_replace(html_entity_decode('&nbsp;'), '', (string)$value);
            $trimmed = trim($cleanedValue);
            if ($trimmed !== '') {
                $lastValue = $trimmed;
            }
            $row[$index] = $lastValue;
        }
        return $row;
    }

    public function collection(Collection $rows)
    {
        $kecamatanRow = $this->fillForward($rows[1]->toArray());
        $kelurahanRow = $this->fillForward($rows[2]->toArray());
        $statusRow = $this->fillForward($rows[3]->toArray());
        $tahunRow = $rows[4]->toArray();

        $currentUsia = '';
        $dimensiCache = [];

        for ($rowIndex = 5; $rowIndex < $rows->count(); $rowIndex++) {
            $row = $rows[$rowIndex];
            if (!isset($row[1])) continue;

            // --- PERBAIKAN 1: Logika Pembacaan Baris Hierarkis ---
            $usiaFromCell = trim((string)($row[0] ?? ''));
            $jenisKelamin = trim((string)($row[1] ?? ''));

            // Selalu update state $currentUsia jika kolom A tidak kosong
            if (!empty($usiaFromCell)) {
                $currentUsia = $usiaFromCell;
            }

            // Lewati baris jika tidak ada data jenis kelamin atau baris jumlah
            if (empty($jenisKelamin) || Str::contains(strtolower($currentUsia), ['jumlah', 'total'])) {
                continue;
            }
            // --- AKHIR PERBAIKAN 1 ---

            $dimensiNamaLengkap = $currentUsia . ' - ' . $jenisKelamin;

            if (!isset($dimensiCache[$dimensiNamaLengkap])) {
                $dimensi = Dimensi::firstOrCreate(
                    ['nama_dimensi' => $dimensiNamaLengkap, 'indikator_id' => $this->indikatorId],
                );
                $dimensiCache[$dimensiNamaLengkap] = $dimensi->id;
            }
            $dimensiId = $dimensiCache[$dimensiNamaLengkap];

            for ($col = 2; $col < count($row); $col++) {
                $nilai = $row[$col];
                if (!is_numeric($nilai)) continue;

                $kecamatan = $kecamatanRow[$col] ?? null;
                $kelurahan = $kelurahanRow[$col] ?? null;
                $status = $statusRow[$col] ?? null;
                $tahun = $tahunRow[$col] ?? null;

                if (!$kecamatan || !$kelurahan || !$status || !$tahun) continue;

                $wilayah = Wilayah::where('kecamatan', $kecamatan)->where('kelurahan', 'like', "%$kelurahan%")->first();
                if (!$wilayah) {
                    Log::warning("Wilayah tidak ditemukan untuk Kec=$kecamatan, Kel=$kelurahan");
                    continue;
                }

                // --- PERBAIKAN 2: Membuat Pencarian Periode Lebih Spesifik ---
                $periode = Periode::firstOrCreate(
                    [
                        'tahun' => (int)$tahun,
                        'jenis_periode' => 'Tahunan' // Jadikan 'jenis_periode' bagian dari kunci pencarian
                    ]
                    // Tidak perlu parameter kedua karena semester null untuk data tahunan
                );
                // --- AKHIR PERBAIKAN 2 ---

                $satuan = $status;

                DataSektoral::updateOrCreate(
                    [
                        'indikator_id' => $this->indikatorId,
                        'wilayah_id' => $wilayah->id,
                        'periode_id' => $periode->id,
                        'dimensi_id' => $dimensiId,
                        'satuan' => $satuan,
                    ],
                    [
                        'nilai' => $nilai,
                        'opd_id' => $this->indikatorOpdId,
                    ]
                );
            }
        }
    }
}