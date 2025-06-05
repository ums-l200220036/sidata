<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    protected $table = 'indikator';

    protected $fillable = [
        'opd_id',
        'nama_indikator',
    ];
}
