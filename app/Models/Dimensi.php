<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dimensi extends Model
{
    protected $table = 'dimensi';

    protected $fillable = [
        'indikator_id',
        'nama_dimensi',
    ];
}
