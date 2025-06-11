<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <-- Tambahkan ini untuk helper string

class Indikator extends Model
{
    protected $table = 'indikator';

    // PERBAIKAN 1: Tambahkan 'route_name' ke dalam $fillable
    protected $fillable = [
        'opd_id',
        'nama_indikator',
        'dimensi_label',
        'route_name' 
    ];

    public function opd()
    {
        return $this->belongsTo(Opd::class);
    }
    
    // PERBAIKAN 2: Gunakan Model Event untuk otomatisasi
    protected static function booted(): void
    {
        // Event ini berjalan TEPAT SEBELUM sebuah indikator baru dibuat
        static::creating(function (Indikator $indikator) {
            $indikator->route_name = self::determineRouteName($indikator->nama_indikator);
        });

        // Event ini berjalan TEPAT SEBELUM sebuah indikator di-update
        static::updating(function (Indikator $indikator) {
            // Hanya update route_name jika nama_indikator berubah
            if ($indikator->isDirty('nama_indikator')) {
                $indikator->route_name = self::determineRouteName($indikator->nama_indikator);
            }
        });
    }

    /**
     * Helper function untuk menentukan nama route berdasarkan nama indikator.
     * Logika ini sekarang terpusat di satu tempat.
     *
     * @param string $namaIndikator
     * @return string
     */
    private static function determineRouteName(string $namaIndikator): string
    {
        $namaLower = Str::lower($namaIndikator);

        if (Str::contains($namaLower, 'pekerjaan')) {
            return 'data.pekerjaan.gender';
        }

        if (Str::contains($namaLower, 'agama')) {
            return 'data.agama.gender';
        }
        
        // Urutkan dari yang paling spesifik ke yang paling umum
        if (Str::contains($namaLower, 'penduduk miskin')) {
            return 'laporan.prioritas';
        }
        
        if (Str::contains($namaLower, 'penduduk rentan')) {
            return 'laporan.prioritas';
        }

        // Defaultnya adalah laporan jenis kelamin umum
        return 'data.gender'; 
    }
}