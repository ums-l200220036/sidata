<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Indikator extends Model
{
    use HasFactory;

    protected $table = 'indikator';

    protected $fillable = [
        'opd_id',
        'nama_indikator',
        'dimensi_label',
        'route_name',
        'import_class'
    ];

    public function opd()
    {
        return $this->belongsTo(Opd::class);
    }

    /**
     * Ini akan berjalan secara otomatis SETIAP KALI
     * Anda membuat atau meng-update sebuah Indikator.
     */
    protected static function booted(): void
    {
        $setAttributes = function (Indikator $indikator) {
            $namaLower = Str::lower($indikator->nama_indikator);

            // --- Logika Otomatis untuk Route Name ---
            if (Str::contains($namaLower, ['miskin', 'rentan'])) {
                $indikator->route_name = 'laporan.prioritas';
            } elseif (Str::contains($namaLower, 'pekerjaan')) {
                $indikator->route_name = 'data.pekerjaan.gender';
            } elseif (Str::contains($namaLower, 'agama')) {
                $indikator->route_name = 'data.agama.gender';
            } else {
                $indikator->route_name = 'laporan.publik'; // Ganti dengan default yang sesuai
            }

            // --- Logika Otomatis untuk Import Class ---
            if (Str::contains($namaLower, ['pegawai', 'usia'])) {
                $indikator->import_class = 'PegawaiUsiaImport';
            } elseif (Str::contains($namaLower, ['miskin', 'rentan'])) {
                $indikator->import_class = 'PrioritasImport'; 
            } else {
                $indikator->import_class = 'DataSektoralImport';
            }
        };

        static::creating($setAttributes);
        static::updating($setAttributes);
    }
}