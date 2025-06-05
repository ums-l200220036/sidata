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
}
