<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';

    protected $fillable = [
        'kelurahan',
        'kecamatan',
        'parent_id',   // jangan lupa tambahkan ini supaya bisa diisi massal
    ];

    // Relasi ke parent (misal kecamatan untuk kelurahan)
    public function parent()
    {
        return $this->belongsTo(Wilayah::class, 'parent_id');
    }

    // Relasi ke children (kelurahan jika ini kecamatan)
    public function children()
    {
        return $this->hasMany(Wilayah::class, 'parent_id');
    }

    // Akses nama wilayah gabungan
    public function getNamaWilayahAttribute()
    {
        if ($this->kelurahan) {
            // jika ada kelurahan, ambil nama kecamatan dari parent
            return "Kelurahan {$this->kelurahan}, Kecamatan " . ($this->parent ? $this->parent->kecamatan : '-');
        } elseif ($this->kecamatan) {
            return "Kecamatan {$this->kecamatan}";
        } else {
            return '-';
        }
    }
}