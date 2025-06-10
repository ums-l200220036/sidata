<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute; // Penting: Import untuk accessor
use App\Models\Wilayah;
use App\Models\Opd; // Penting: Import model Opd

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

    /**
     * Accessor untuk mendapatkan nama afiliasi berdasarkan role pengguna.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function affiliationName(): Attribute // Perubahan di sini, menggunakan Attribute class
    {
        return Attribute::make(
            get: function () {
                if ($this->role === 'opd' && $this->opd) {
                    return $this->opd->nama_opd;
                }
                // Logika untuk role 'kecamatan' dan 'kelurahan'
                // Kita perlu memastikan bahwa `wilayah` ada dan juga `opd` jika role-nya 'kecamatan'
                // karena kecamatan memiliki opd_id juga.
                elseif ($this->role === 'kecamatan' && $this->wilayah) {
                    // Jika role adalah kecamatan, kita bisa tampilkan nama kecamatannya
                    return 'Kecamatan ' . $this->wilayah->kecamatan;
                }
                elseif ($this->role === 'kelurahan' && $this->wilayah) {
                    // Untuk kelurahan, tampilkan kelurahan dan kecamatannya
                    // Asumsi model Wilayah memiliki relasi 'parent' ke kecamatan
                    // jika struktur wilayah Anda adalah kelurahan-kecamatan.
                    // Jika tidak, Anda mungkin perlu menyesuaikan bagaimana Anda mendapatkan nama kecamatan.
                    if ($this->wilayah->kelurahan) {
                        // Cek apakah ada parent (kecamatan) dari kelurahan ini
                        if ($this->wilayah->parent) {
                            return 'Kelurahan ' . $this->wilayah->kelurahan . ' (' . $this->wilayah->parent->kecamatan . ')';
                        }
                        return 'Kelurahan ' . $this->wilayah->kelurahan; // Fallback jika tidak ada parent
                    }
                }
                // Untuk role 'admin' atau jika tidak ada afiliasi yang cocok
                return '-';
            },
        );
    }
}