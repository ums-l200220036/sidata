<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSektoral extends Model
{
    protected $table = 'data_sektoral';

    protected $fillable = [
        'indikator_id',
        'opd_id',
        'wilayah_id',
        'periode_id',
        'dimensi_id',
        'nilai',
        'satuan',
    ];

        public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'indikator_id');
    }

    public function dimensi()
    {
        return $this->belongsTo(Dimensi::class, 'dimensi_id');
    }
}
