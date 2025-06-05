<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periode';

    protected $fillable = [
        'jenis_periode',
        'tahun',
        'semester',
    ];
}
