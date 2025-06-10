<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Import Str facade

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // --- Mapping Nama Singkat/Username untuk OPD dan Wilayah ---
        $nameMappings = [
            // OPD (Non-Kecamatan)
            'Badan Kepegawaian Dan Pengembangan Sumberdaya Manusia' => 'bkpsdm',
            'Badan Pengelola Keuangan Dan Aset Daerah' => 'bppkad',
            'Badan Perencanaan Pembangunan Daerah' => 'bappeda',
            'Dinas Pendidikan' => 'disdik',
            'Dinas Kesehatan' => 'dinkes',
            'Dinas Pekerjaan Umum dan Penataan Ruang' => 'dpupr',
            'Dinas Perumahan dan Kawasan Permukiman serta Pertanahan' => 'dprkpp',
            'Satuan Polisi Pamong Praja' => 'satpolpp',
            'Dinas Pemadam Kebakaran' => 'damkar',
            'Badan Penanggulangan Bencana Daerah' => 'bpbd',
            'Dinas Sosial' => 'dinsos',
            'Dinas Pemberdayaan Perempuan Dan Pelindungan Anak Serta Pengendalian Penduduk Dan Keluarga Berencana' => 'dpppa',
            'Dinas Ketahanan Pangan dan Pertanian' => 'dkpp',
            'Dinas Lingkungan Hidup' => 'dlh',
            'Dinas Kependudukan dan Pencatatan Sipil' => 'disdukcapil',
            'Dinas Perhubungan' => 'dishub',
            'Dinas Komunikasi, Informatika, Statistik dan Persandian' => 'diskominfo',
            'Dinas Koperasi Usaha Kecil Dan Menengah Dan Perindustrian' => 'dinkopukm',
            'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu' => 'dpmptsp',
            'Dinas Kepemudaan dan Olah Raga' => 'dispora',
            'Dinas Perpustakaan dan Kearsipan' => 'dispusip',
            'Dinas Perdagangan' => 'disdag',
            'Dinas Tenaga Kerja' => 'disnaker',
            'Sekretariat Daerah' => 'sekda',
            'Sekretariat DPRD' => 'setwan',
            'Inspektorat' => 'inspektorat',
            'Badan Kesatuan Bangsa Dan Politik' => 'kesbangpol',
            'Badan Pendapatan Daerah' => 'bapenda',
            'Dinas Kebudayaan Dan Pariwisata' => 'disbudpar',
            'Badan Riset dan Inovasi Daerah' => 'brida',
            'RSUD Bung Karno' => 'rsudbk',

            // Kecamatan (nama OPD dan Wilayah sama setelah di-strip)
            'Kecamatan Banjarsari' => 'banjarsari', // Ini adalah nama OPD
            'Kecamatan Jebres' => 'jebres',
            'Kecamatan Laweyan' => 'laweyan',
            'Kecamatan Pasar Kliwon' => 'pasarkliwon',
            'Kecamatan Serengan' => 'serengan',

            // Kelurahan - dari daftar 54 kelurahan
            // Banjarsari (15)
            'Banyuanyar' => 'banyuanyar', 'Banjarsari' => 'banjarsari_kel', 'Gilingan' => 'gilingan',
            'Joglo' => 'joglo', 'Kadipiro' => 'kadipiro', 'Keprabon' => 'keprabon',
            'Kestalan' => 'kestalan', 'Ketelan' => 'ketelan', 'Manahan' => 'manahan',
            'Mangkubumen' => 'mangkubumen_kel',
            'Nusukan' => 'nusukan', 'Punggawan' => 'punggawan', 'Setabelan' => 'setabelan',
            'Sumber' => 'sumber', 'Timuran' => 'timuran',

            // Jebres (11)
            'Gandekan' => 'gandekan', 'Jagalan' => 'jagalan', 'Jebres' => 'jebres_kel',
            'Kepatihan Kulon' => 'kepatihankulon', 'Kepatihan Wetan' => 'kepatihanwetan',
            'Mojosongo' => 'mojosongo', 'Pucangsawit' => 'pucangsawit',
            'Purwodiningratan' => 'purwodiningratan', 'Sewu' => 'sewu',
            'Sudiroprajan' => 'sudiroprajan', 'Tegalharjo' => 'tegalharjo',

            // Laweyan (11)
            'Bumi' => 'bumi', 'Jajar' => 'jajar', 'Karangasem' => 'karangasem',
            'Kerten' => 'kerten', 'Laweyan' => 'laweyan_kel', 'Pajang' => 'pajang',
            'Panularan' => 'panularan', 'Penumping' => 'penumping', 'Purwosari' => 'purwosari_kel',
            'Sondakan' => 'sondakan', 'Sriwedari' => 'sriwedari',

            // Pasar Kliwon (10)
            'Baluwarti' => 'baluwarti', 'Gajahan' => 'gajahan', 'Joyosuran' => 'joyosuran',
            'Kampung Baru' => 'kampungbaru_kel',
            'Kauman' => 'kauman', 'Kedung Lumbu' => 'kedunglumbu',
            'Mojo' => 'mojo', 'Pasar Kliwon' => 'pasarkliwon_kel',
            'Sangkrah' => 'sangkrah', 'Semanggi' => 'semanggi',

            // Serengan (7)
            'Danukusuman' => 'danukusuman', 'Jayengan' => 'jayengan', 'Joyotakan' => 'joyotakan',
            'Kemlayan' => 'kemlayan', 'Kratonan' => 'kratonan', 'Serengan' => 'serengan_kel',
            'Tipes' => 'tipes',
        ];

        // Helper untuk menghasilkan password berdasarkan nama yang sudah diclean
        $generatePassword = function ($cleanedBaseName) {
            $sluggedName = Str::slug($cleanedBaseName, '');
            $shortenedName = Str::limit($sluggedName, 20, '');
            return Hash::make($shortenedName . '123');
        };

        // Helper untuk menghasilkan bagian awal email (username)
        $generateEmailPrefix = function ($name, $nameMappings) {
            // Prioritas pertama: cari di mapping
            if (isset($nameMappings[$name])) {
                return $nameMappings[$name];
            }
            // Prioritas kedua: gunakan Str::slug jika tidak ada di mapping
            // Ini juga akan membersihkan string dari "Kecamatan " jika nama OPD tidak ada di mapping
            return Str::slug(str_replace('Kecamatan ', '', $name), '');
        };

        // === 1. Data Admin Sistem ===
        $adminBaseName = 'admin';
        DB::table('users')->insert([
            'name' => 'Admin Sistem',
            'email' => $generateEmailPrefix($adminBaseName, $nameMappings) . '@surakarta.go.id',
            'password' => $generatePassword($adminBaseName),
            'role' => 'admin',
            'opd_id' => null,
            'wilayah_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === 2. Data User OPD (Non-Kecamatan) ===
        $opds = DB::table('opd')->whereNotIn('nama_opd', [
            'Kecamatan Banjarsari', 'Kecamatan Jebres', 'Kecamatan Laweyan',
            'Kecamatan Pasar Kliwon', 'Kecamatan Serengan'
        ])->get();

        foreach ($opds as $opd) {
            $baseName = $generateEmailPrefix($opd->nama_opd, $nameMappings);
            DB::table('users')->insert([
                'name' => $opd->nama_opd,
                'email' => $baseName . '@surakarta.go.id',
                'password' => $generatePassword($baseName),
                'role' => 'opd',
                'opd_id' => $opd->id,
                'wilayah_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // === 3. Data User OPD Kecamatan ===
        $kecamatansOpd = DB::table('opd')->whereIn('nama_opd', [
            'Kecamatan Banjarsari', 'Kecamatan Jebres', 'Kecamatan Laweyan',
            'Kecamatan Pasar Kliwon', 'Kecamatan Serengan'
        ])->get();

        foreach ($kecamatansOpd as $kecamatanOpd) {
            // Hapus prefix "Kecamatan " saat mencari di tabel wilayah
            $strippedKecamatanName = str_replace('Kecamatan ', '', $kecamatanOpd->nama_opd);

            $wilayahKecamatan = DB::table('wilayah')
                                ->where('kecamatan', $strippedKecamatanName) // Diperbaiki di sini
                                ->whereNull('kelurahan') // Pastikan ini adalah entri kecamatan induk
                                ->first();

            if ($wilayahKecamatan) {
                $baseName = $generateEmailPrefix($kecamatanOpd->nama_opd, $nameMappings);
                DB::table('users')->insert([
                    'name' => $kecamatanOpd->nama_opd,
                    'email' => $baseName . '@surakarta.go.id',
                    'password' => $generatePassword($baseName),
                    'role' => 'kecamatan',
                    'opd_id' => $kecamatanOpd->id,
                    'wilayah_id' => $wilayahKecamatan->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // Opsional: Log pesan jika tidak ditemukan, untuk debugging
                // $this->command->info("Wilayah Kecamatan untuk OPD '{$kecamatanOpd->nama_opd}' (stripped: '{$strippedKecamatanName}') tidak ditemukan di tabel wilayah.");
            }
        }

        // === 4. Data User Kelurahan ===
        $kelurahans = DB::table('wilayah')->whereNotNull('kelurahan')->get();

        foreach ($kelurahans as $kelurahan) {
            $baseName = $generateEmailPrefix($kelurahan->kelurahan, $nameMappings);
            DB::table('users')->insert([
                'name' => $kelurahan->kelurahan,
                'email' => $baseName . '@surakarta.go.id',
                'password' => $generatePassword($baseName),
                'role' => 'kelurahan',
                'opd_id' => null,
                'wilayah_id' => $kelurahan->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}