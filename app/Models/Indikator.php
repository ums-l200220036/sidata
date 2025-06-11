<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    protected $table = 'indikator';

    protected $fillable = [
        'opd_id',
        'nama_indikator',
        'dimensi_label'
    ];

    public function opd()
    {
        return $this->belongsTo(Opd::class);
    }

    public function getRouteNameAttribute(): string
    {
        // Ubah nama indikator menjadi huruf kecil untuk perbandingan yang konsisten
        $namaIndikatorLower = strtolower($this->nama_indikator);

        // Jika nama indikator mengandung kata 'pendidikan'
        if (str_contains($namaIndikatorLower, 'pendidikan')) {
            return 'data.gender'; // Kembalikan nama route untuk pendidikan
        }
        
        // Jika nama indikator mengandung kata 'pekerjaan'
        if (str_contains($namaIndikatorLower, 'pekerjaan')) {
            return 'data.pekerjaan.gender'; // Kembalikan nama route untuk pekerjaan
        }

        // Tambahkan kondisi lain di sini untuk indikator lainnya di masa depan
        if (str_contains($namaIndikatorLower, 'agama')) {
            return 'data.agama.gender';
        }

        // Jika tidak ada yang cocok, kembalikan route default atau halaman error
        return 'dashboard'; // Mengembalikan '#' agar link tidak rusak
    }
}
