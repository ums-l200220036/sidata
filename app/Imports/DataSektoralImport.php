<?php

namespace App\Imports;

use App\Models\DataSektoral;
use App\Models\Dimensi;
use App\Models\Periode;
use App\Models\Wilayah;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class DataSektoralImport implements ToCollection
{
    protected $indikatorId;
    protected $indikatorOpdId;

    public function __construct($indikatorId, $indikatorOpdId)
    {
        $this->indikatorId = $indikatorId;
        $this->indikatorOpdId = $indikatorOpdId;
    }

    /**
     * Fungsi untuk mengisi nilai-nilai kosong pada baris header (fill forward).
     * Ini membantu mengatasi sel yang di-merge atau kosong di Excel.
     */
    protected function fillForward(array $row): array
    {
        $lastValue = null;
        foreach ($row as $index => $value) {
            // Pastikan nilai adalah string sebelum diproses
            $cleanedValue = str_replace(html_entity_decode('&nbsp;'), '', (string) $value);
            $trimmed = trim($cleanedValue);
            if ($trimmed !== '') {
                $lastValue = $trimmed;
                $row[$index] = $lastValue;
            } else {
                $row[$index] = $lastValue;
            }
        }
        return $row;
    }

    /**
     * Memproses data Excel dari collection.
     */
    public function collection(Collection $rows)
    {
        // Membaca baris-baris header dari file Excel (baris 2-5, index 1-4)
        $kecamatanRow = $this->fillForward($rows[1]->toArray()); // Baris 2 Excel
        $kelurahanRow = $this->fillForward($rows[2]->toArray()); // Baris 3 Excel
        $periodeRow = $this->fillForward($rows[3]->toArray());   // Baris 4 Excel
        $baris5 = $rows[4]->toArray();                           // Baris 5 Excel (gender atau jenis satuan)

        $dimensiCache = []; // Cache untuk dimensi agar tidak query berulang

        // Deteksi jenis header di baris 5 untuk menentukan 'satuan'
        $isGenderBased = false;
        $isJenisSatuan = false;
        foreach ($baris5 as $val) {
            // Memastikan $val adalah string sebelum trim() dan strtoupper()
            // Ini mengatasi jika sel di baris 5 kosong (null)
            $val = strtoupper(trim((string) $val));
            if (in_array($val, ['L', 'P'])) {
                $isGenderBased = true;
                break;
            } elseif (in_array(strtolower($val), ['individu', 'keluarga'])) {
                $isJenisSatuan = true;
                break;
            }
        }

        // Loop data utama dari baris ke-6 (index 5) hingga akhir file
        for ($rowIndex = 5; $rowIndex < $rows->count(); $rowIndex++) {
            $row = $rows[$rowIndex];
            
            // Mengambil nama dimensi dari kolom pertama (index 0)
            // Memastikan $row[0] adalah string sebelum di-trim.
            // Ini mengatasi jika sel dimensi kosong (null)
            $dimensiNama = trim((string)($row[0] ?? ''));

            // Lewati baris jika nama dimensi kosong atau berisi kata 'jumlah'
            if (empty($dimensiNama) || Str::contains(strtolower($dimensiNama), 'jumlah')) {
                continue;
            }

            // Cari atau buat dimensi baru berdasarkan nama dimensi dan indikator ID
            if (!isset($dimensiCache[$dimensiNama])) {
                $dimensi = Dimensi::firstOrCreate([
                    'nama_dimensi' => $dimensiNama,
                    'indikator_id' => $this->indikatorId,
                ]);
                $dimensiCache[$dimensiNama] = $dimensi->id;
            }

            // Loop setiap kolom data pada baris saat ini (mulai dari kolom kedua, index 1)
            for ($col = 1; $col < count($row); $col++) {
                $nilai = $row[$col];
                // Lewati jika nilai bukan angka (misal: teks kosong)
                if (!is_numeric($nilai)) {
                    continue;
                }

                // Ambil data kecamatan, kelurahan, dan periode dari baris header yang sudah di-fill forward
                $kecamatan = $kecamatanRow[$col] ?? '';
                $kelurahan = $kelurahanRow[$col] ?? '';
                $periodeStr = $periodeRow[$col] ?? '';

                // Lewati jika kecamatan dan kelurahan kosong
                if (empty($kecamatan) && empty($kelurahan)) {
                    continue;
                }

                // Normalisasi dan parsing string periode (contoh: "Tahun 2023 Semester 1")
                $periodeStr = str_ireplace('Semeter', 'Semester', $periodeStr); // Koreksi typo "Semeter"
                if (!preg_match('/Tahun (\d{4}) Semester (\d)/i', $periodeStr, $matches)) {
                    continue; // Lewati jika format periode tidak sesuai
                }
                $tahun = $matches[1];
                $semester = $matches[2];

                // Cari atau buat wilayah berdasarkan kecamatan dan kelurahan
                $wilayah = Wilayah::where('kecamatan', $kecamatan)
                                  ->where('kelurahan', 'like', "%$kelurahan%")
                                  ->first();
                if (!$wilayah) { 
                    continue; // Lewati jika wilayah tidak ditemukan
                }

                // Cari atau buat periode baru
                $periode = Periode::firstOrCreate(
                    ['tahun' => $tahun, 'semester' => $semester],
                    ['jenis_periode' => 'Semesteran'] // Default jenis periode
                );

                // Tentukan 'satuan' berdasarkan deteksi header
                $satuan = 'Jiwa'; // Satuan default
                if ($isGenderBased) {
                    // Memastikan nilai $baris5[$col] adalah string sebelum trim()
                    $jk = strtoupper(trim((string)($baris5[$col] ?? '')));
                    if (in_array($jk, ['L', 'P'])) { 
                        $satuan = "Jiwa ($jk)"; 
                    }
                } elseif ($isJenisSatuan) {
                    // Memastikan nilai $baris5[$col] adalah string sebelum trim()
                    $jenis = ucfirst(strtolower(trim((string)($baris5[$col] ?? ''))));
                    if (in_array($jenis, ['Individu', 'Keluarga'])) { 
                        $satuan = $jenis; 
                    }
                }

                // Membuat atau memperbarui data sektoral
                // Gunakan updateOrCreate untuk menghindari duplikasi data
                DataSektoral::updateOrCreate(
                    [
                        'indikator_id' => $this->indikatorId,
                        'wilayah_id' => $wilayah->id,
                        'periode_id' => $periode->id,
                        'dimensi_id' => $dimensiCache[$dimensiNama],
                        'satuan' => $satuan,
                    ],
                    [
                        'opd_id' => $this->indikatorOpdId,
                        'nilai' => $nilai,
                    ]
                );
            }
        }
    }
}