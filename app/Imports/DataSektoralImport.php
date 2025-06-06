<?php

namespace App\Imports;

use App\Models\Dimensi;
use App\Models\DataSektoral;
use App\Models\Wilayah;
use App\Models\Periode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class DataSektoralImport implements ToCollection
{
    protected $indikatorId;
    protected $indikatorOpdId;

    public function __construct($indikatorId, $indikatorOpdId)
    {
        $this->indikatorId = $indikatorId;
        $this->indikatorOpdId = $indikatorOpdId;
    }

    // Fungsi untuk fill forward merge kosong pada baris header
    protected function fillForward(array $row): array
    {
        $lastValue = null;
        foreach ($row as $index => $value) {
            $trimmed = trim((string)$value);
            if ($trimmed !== '') {
                $lastValue = $trimmed;
                $row[$index] = $lastValue;
            } else {
                $row[$index] = $lastValue;
            }
        }
        return $row;
    }

    public function collection(Collection $rows)
    {
        $kecamatanRow = $this->fillForward($rows[1]->toArray()); // Baris 2
        $kelurahanRow = $this->fillForward($rows[2]->toArray()); // Baris 3
        $periodeRow = $this->fillForward($rows[3]->toArray());   // Baris 4
        $baris5 = $rows[4]->toArray();                            // Baris 5 (gender atau jenis satuan)

        $dimensiCache = [];

        // Deteksi jenis header baris 5: L/P (gender) atau Individu/Keluarga
        $isGenderBased = false;
        $isJenisSatuan = false;

        foreach ($baris5 as $val) {
            $val = strtoupper(trim($val));
            if (in_array($val, ['L', 'P'])) {
                $isGenderBased = true;
                break;
            } elseif (in_array(strtolower($val), ['individu', 'keluarga'])) {
                $isJenisSatuan = true;
                break;
            }
        }

        // Loop data dari baris ke-6 (index 5)
        for ($rowIndex = 5; $rowIndex < $rows->count(); $rowIndex++) {
            $row = $rows[$rowIndex];

            $dimensiNama = trim($row[0]);
            if (!$dimensiNama || Str::contains(strtolower($dimensiNama), 'jumlah')) {
                continue;
            }

            // Cache dan insert dimensi
            if (!isset($dimensiCache[$dimensiNama])) {
                $dimensi = Dimensi::firstOrCreate([
                    'nama_dimensi' => $dimensiNama,
                    'indikator_id' => $this->indikatorId,
                ]);
                $dimensiCache[$dimensiNama] = $dimensi->id;
                Log::info("Dimensi dibuat dengan ID {$dimensi->id} untuk nama: $dimensiNama");
            }

            // Loop kolom data (mulai dari kolom 1, karena kolom 0 adalah dimensi)
            for ($col = 1; $col < count($row); $col++) {
                $nilai = $row[$col];

                if (!is_numeric($nilai)) {
                    continue;
                }

                $kecamatan = $kecamatanRow[$col] ?? '';
                $kelurahan = $kelurahanRow[$col] ?? '';
                $periodeStr = $periodeRow[$col] ?? '';

                if (empty($kecamatan) && empty($kelurahan)) {
                    Log::warning("Skip kolom $col karena kecamatan dan kelurahan kosong");
                    continue;
                }

                // Normalisasi periode
                $periodeStr = str_ireplace('Semeter', 'Semester', $periodeStr);
                if (!preg_match('/Tahun (\d{4}) Semester (\d)/i', $periodeStr, $matches)) {
                    Log::warning("Gagal membaca periode di kolom $col: '$periodeStr'");
                    continue;
                }

                $tahun = $matches[1];
                $semester = $matches[2];

                // Cari wilayah
                $wilayah = Wilayah::where('kecamatan', $kecamatan)
                    ->where('kelurahan', $kelurahan)
                    ->first();

                if (!$wilayah) {
                    Log::warning("Wilayah tidak ditemukan: Kec=$kecamatan, Kel=$kelurahan");
                    continue;
                }

                // Cari periode
                $periode = Periode::firstOrCreate([
                    'jenis_periode' => 'Semesteran',
                    'tahun' => $tahun,
                    'semester' => $semester,
                ]);

                // Tentukan satuan
                $satuan = 'Jiwa';
                if ($isGenderBased) {
                    $jk = strtoupper($baris5[$col] ?? '');
                    if (in_array($jk, ['L', 'P'])) {
                        $satuan = "Jiwa ($jk)";
                    }
                } elseif ($isJenisSatuan) {
                    $jenis = ucfirst(strtolower($baris5[$col] ?? ''));
                    if (in_array($jenis, ['Individu', 'Keluarga'])) {
                        $satuan = $jenis;
                    }
                }

                // Simpan data
                DataSektoral::create([
                    'indikator_id' => $this->indikatorId,
                    'opd_id' => $this->indikatorOpdId,
                    'wilayah_id' => $wilayah->id,
                    'periode_id' => $periode->id,
                    'dimensi_id' => $dimensiCache[$dimensiNama],
                    'nilai' => $nilai,
                    'satuan' => $satuan,
                ]);

                Log::info("Data disimpan: $dimensiNama, $kecamatan-$kelurahan, $tahun-S$semester, Satuan: $satuan, Nilai: $nilai");
            }
        }
    }
}