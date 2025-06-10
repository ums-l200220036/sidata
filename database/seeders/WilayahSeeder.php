<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // === SISIPKAN KECAMATAN TERLEBIH DAHULU ===
        DB::table('wilayah')->insert([
            [
                'parent_id' => null,
                'kecamatan' => 'Banjarsari',
                'kelurahan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'kecamatan' => 'Jebres',
                'kelurahan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'kecamatan' => 'Laweyan',
                'kelurahan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'kecamatan' => 'Pasar Kliwon', // Pastikan konsisten dengan nama di daftar OPD Anda
                'kelurahan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'kecamatan' => 'Serengan',
                'kelurahan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // === AMBIL ID KECAMATAN YANG SUDAH DISISIPKAN ===
        $banjarsariId = DB::table('wilayah')->where('kecamatan', 'Banjarsari')->whereNull('kelurahan')->first()->id;
        $jebresId = DB::table('wilayah')->where('kecamatan', 'Jebres')->whereNull('kelurahan')->first()->id;
        $laweyanId = DB::table('wilayah')->where('kecamatan', 'Laweyan')->whereNull('kelurahan')->first()->id;
        $pasarkliwonId = DB::table('wilayah')->where('kecamatan', 'Pasar Kliwon')->whereNull('kelurahan')->first()->id;
        $serenganId = DB::table('wilayah')->where('kecamatan', 'Serengan')->whereNull('kelurahan')->first()->id;

        // === SISIPKAN KELURAHAN BERDASARKAN DATA YANG DIBERIKAN ===
        DB::table('wilayah')->insert([
            // Kelurahan Banjarsari (15)
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Banyuanyar', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Banjarsari', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Gilingan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Joglo', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Kadipiro', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Keprabon', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Kestalan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Ketelan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Manahan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Mangkubumen', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Nusukan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Punggawan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Setabelan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Sumber', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $banjarsariId, 'kecamatan' => 'Banjarsari', 'kelurahan' => 'Timuran', 'created_at' => now(), 'updated_at' => now() ],

            // Kelurahan Jebres (11)
            [ 'parent_id' => $jebresId, 'kecamatan' => 'Jebres', 'kelurahan' => 'Gandekan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $jebresId, 'kecamatan' => 'Jebres', 'kelurahan' => 'Jagalan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $jebresId, 'kecamatan' => 'Jebres', 'kelurahan' => 'Jebres', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $jebresId, 'kecamatan' => 'Jebres', 'kelurahan' => 'Kepatihan Kulon', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $jebresId, 'kecamatan' => 'Jebres', 'kelurahan' => 'Kepatihan Wetan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $jebresId, 'kecamatan' => 'Jebres', 'kelurahan' => 'Mojosongo', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $jebresId, 'kecamatan' => 'Jebres', 'kelurahan' => 'Pucangsawit', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $jebresId, 'kecamatan' => 'Jebres', 'kelurahan' => 'Purwodiningratan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $jebresId, 'kecamatan' => 'Jebres', 'kelurahan' => 'Sewu', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $jebresId, 'kecamatan' => 'Jebres', 'kelurahan' => 'Sudiroprajan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $jebresId, 'kecamatan' => 'Jebres', 'kelurahan' => 'Tegalharjo', 'created_at' => now(), 'updated_at' => now() ],

            // Kelurahan Laweyan (11)
            [ 'parent_id' => $laweyanId, 'kecamatan' => 'Laweyan', 'kelurahan' => 'Bumi', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $laweyanId, 'kecamatan' => 'Laweyan', 'kelurahan' => 'Jajar', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $laweyanId, 'kecamatan' => 'Laweyan', 'kelurahan' => 'Karangasem', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $laweyanId, 'kecamatan' => 'Laweyan', 'kelurahan' => 'Kerten', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $laweyanId, 'kecamatan' => 'Laweyan', 'kelurahan' => 'Laweyan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $laweyanId, 'kecamatan' => 'Laweyan', 'kelurahan' => 'Pajang', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $laweyanId, 'kecamatan' => 'Laweyan', 'kelurahan' => 'Panularan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $laweyanId, 'kecamatan' => 'Laweyan', 'kelurahan' => 'Penumping', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $laweyanId, 'kecamatan' => 'Laweyan', 'kelurahan' => 'Purwosari', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $laweyanId, 'kecamatan' => 'Laweyan', 'kelurahan' => 'Sondakan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $laweyanId, 'kecamatan' => 'Laweyan', 'kelurahan' => 'Sriwedari', 'created_at' => now(), 'updated_at' => now() ],

            // Kelurahan Pasarkliwon (10)
            [ 'parent_id' => $pasarkliwonId, 'kecamatan' => 'Pasar Kliwon', 'kelurahan' => 'Baluwarti', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $pasarkliwonId, 'kecamatan' => 'Pasar Kliwon', 'kelurahan' => 'Gajahan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $pasarkliwonId, 'kecamatan' => 'Pasar Kliwon', 'kelurahan' => 'Joyosuran', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $pasarkliwonId, 'kecamatan' => 'Pasar Kliwon', 'kelurahan' => 'Kampung Baru', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $pasarkliwonId, 'kecamatan' => 'Pasar Kliwon', 'kelurahan' => 'Kauman', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $pasarkliwonId, 'kecamatan' => 'Pasar Kliwon', 'kelurahan' => 'Kedung Lumbu', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $pasarkliwonId, 'kecamatan' => 'Pasar Kliwon', 'kelurahan' => 'Mojo', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $pasarkliwonId, 'kecamatan' => 'Pasar Kliwon', 'kelurahan' => 'Pasar Kliwon', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $pasarkliwonId, 'kecamatan' => 'Pasar Kliwon', 'kelurahan' => 'Sangkrah', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $pasarkliwonId, 'kecamatan' => 'Pasar Kliwon', 'kelurahan' => 'Semanggi', 'created_at' => now(), 'updated_at' => now() ],

            // Kelurahan Serengan (7)
            [ 'parent_id' => $serenganId, 'kecamatan' => 'Serengan', 'kelurahan' => 'Danukusuman', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $serenganId, 'kecamatan' => 'Serengan', 'kelurahan' => 'Jayengan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $serenganId, 'kecamatan' => 'Serengan', 'kelurahan' => 'Joyotakan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $serenganId, 'kecamatan' => 'Serengan', 'kelurahan' => 'Kemlayan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $serenganId, 'kecamatan' => 'Serengan', 'kelurahan' => 'Kratonan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $serenganId, 'kecamatan' => 'Serengan', 'kelurahan' => 'Serengan', 'created_at' => now(), 'updated_at' => now() ],
            [ 'parent_id' => $serenganId, 'kecamatan' => 'Serengan', 'kelurahan' => 'Tipes', 'created_at' => now(), 'updated_at' => now() ],
        ]);
    }
}