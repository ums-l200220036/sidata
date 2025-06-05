<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OPD extends Model
{
    protected $table = 'opd';

    protected $fillable = [
        'nama_opd',
    ];
}
