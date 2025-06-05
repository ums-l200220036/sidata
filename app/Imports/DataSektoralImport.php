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
        // Ambil baris header yang berisi kecamatan, kelurahan, periode, dan gender
        $kecamatanRow = $this->fillForward($rows[1]->toArray()); // Baris 2 Excel (index 1)
        $kelurahanRow = $this->fillForward($rows[2]->toArray()); // Baris 3 Excel (index 2)
        $periodeRow = $this->fillForward($rows[3]->toArray());   // Baris 4 Excel (index 3)
        $genderRow = $rows[4]->toArray();                        // Baris 5 Excel (index 4), biasanya tidak merge

        $dimensiCache = [];

        // Loop mulai dari baris 6 (index 5) sampai akhir
        for ($rowIndex = 5; $rowIndex < $rows->count(); $rowIndex++) {
            $row = $rows[$rowIndex];

            $dimensiNama = trim($row[0]);
            if (!$dimensiNama || Str::contains(strtolower($dimensiNama), 'jumlah')) {
                continue; // Lewati baris kosong atau judul "Jumlah"
            }

            // Simpan dimensi jika belum ada
            if (!isset($dimensiCache[$dimensiNama])) {
                $dimensi = Dimensi::firstOrCreate([
                    'nama_dimensi' => $dimensiNama,
                    'indikator_id' => $this->indikatorId,
                ]);
                $dimensiCache[$dimensiNama] = $dimensi->id;
                Log::info("Dimensi dibuat dengan ID {$dimensi->id} untuk nama: $dimensiNama");
            }

            // Loop kolom mulai kolom 3 (index 2) — sesuaikan jika kolom data berbeda
            for ($col = 1; $col < count($row); $col++) {
                $nilai = $row[$col];
                if (!is_numeric($nilai)) {
                    continue; // Skip jika bukan angka
                }

                $kecamatan = $kecamatanRow[$col] ?? '';
                $kelurahan = $kelurahanRow[$col] ?? '';
                $periodeStr = $periodeRow[$col] ?? '';
                $jenisKelamin = strtoupper($genderRow[$col] ?? '');

                if (empty($kecamatan) && empty($kelurahan)) {
                    Log::warning("Skip kolom $col karena kecamatan dan kelurahan kosong");
                    continue;
                }

                // Perbaiki typo 'Semeter' -> 'Semester'
                $periodeStr = str_ireplace('Semeter', 'Semester', $periodeStr);

                if (empty($periodeStr)) {
                    Log::warning("Skip kolom $col karena periode kosong");
                    continue;
                }

                if (!preg_match('/Tahun (\d{4}) Semester (\d)/i', $periodeStr, $matches)) {
                    Log::warning("Gagal membaca periode di kolom $col: '$periodeStr'");
                    continue;
                }

                $tahun = $matches[1];
                $semester = $matches[2];

                $wilayah = Wilayah::where('kecamatan', $kecamatan)
                    ->where('kelurahan', $kelurahan)
                    ->first();

                if (!$wilayah) {
                    Log::warning("Wilayah tidak ditemukan: Kec=$kecamatan, Kel=$kelurahan");
                    continue;
                }

                $periode = Periode::firstOrCreate([
                    'jenis_periode' => 'Semesteran',
                    'tahun' => $tahun,
                    'semester' => $semester,
                ]);

                $satuan = 'Jiwa';
                if (in_array($jenisKelamin, ['L', 'P'])) {
                    $satuan .= " ($jenisKelamin)";
                }

                DataSektoral::create([
                    'indikator_id' => $this->indikatorId,
                    'opd_id' => $this->indikatorOpdId,
                    'wilayah_id' => $wilayah->id,
                    'periode_id' => $periode->id,
                    'dimensi_id' => $dimensiCache[$dimensiNama],
                    'nilai' => $nilai,
                    'satuan' => $satuan,
                ]);

                Log::info("Data sektoral disimpan: dimensi $dimensiNama, wilayah $kecamatan-$kelurahan, periode $tahun semester $semester, jenis kelamin $jenisKelamin, nilai $nilai");
            }
        }
    }
}