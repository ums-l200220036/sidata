<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Wilayah;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'opd_id',
        'role',
        'wilayah_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

        public function opd()
    {
        return $this->belongsTo(Opd::class);
    }

    public function getAffiliationNameAttribute()
    {
        // Jika user memiliki opd_id (berarti role opd atau kecamatan)
        if ($this->opd) {
            return $this->opd->nama_opd;
        }
        // Jika user memiliki wilayah_id (berarti role kecamatan atau kelurahan)
        // Dan tidak memiliki opd_id (misal kelurahan)
        // Atau jika opd_id nya adalah sebuah kecamatan, kita bisa menampilkan nama wilayah juga
        elseif ($this->wilayah) {
            // Jika wilayah ini adalah kelurahan (punya nama kelurahan)
            if ($this->wilayah->kelurahan) {
                return $this->wilayah->kelurahan . ' (' . $this->wilayah->kecamatan . ')';
            }
            // Jika wilayah ini adalah kecamatan (tidak punya nama kelurahan)
            return $this->wilayah->kecamatan;
        }
        // Jika tidak memiliki afiliasi (misal admin)
        return '-';
    }
}
