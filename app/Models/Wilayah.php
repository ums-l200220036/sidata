<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';

    protected $fillable = [
        'kelurahan',
        'kecamatan',
        'parent_id',
        'created_at',
        'updated_at',
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
    public function getNamaLengkapAttribute()
    {
        if ($this->kelurahan && $this->parent) {
            return $this->kelurahan . ' (' . $this->parent->kecamatan . ')';
        }
        return $this->kecamatan;
    }
}