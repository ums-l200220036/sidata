<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('opd')->insert([
            ['nama_opd' => 'Dinas Pendidikan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Kesehatan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Pekerjaan Umum dan Penataan Ruang', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Perumahan dan Kawasan Permukiman serta Pertanahan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Satuan Polisi Pamong Praja', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Pemadam Kebakaran', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Badan Penanggulangan Bencana Daerah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Sosial', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Pemberdayaan Perempuan Dan Pelindungan Anak Serta Pengendalian Penduduk Dan Keluarga Berencana', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Ketahanan Pangan dan Pertanian', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Lingkungan Hidup', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Kependudukan dan Pencatatan Sipil', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Perhubungan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Komunikasi, Informatika, Statistik dan Persandian', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Koperasi Usaha Kecil Dan Menengah Dan Perindustrian', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Kepemudaan dan Olah Raga', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Perpustakaan dan Kearsipan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Perdagangan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Tenaga Kerja', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Sekretariat Daerah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Sekretariat DPRD', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Badan Perencanaan Pembangunan Daerah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Badan Pengelola Keuangan Dan Aset Daerah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Badan Kepegawaian Dan Pengembangan Sumberdaya Manusia', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Inspektorat', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Kecamatan Banjarsari', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Badan Kesatuan Bangsa Dan Politik', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Kecamatan Jebres', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Kecamatan Laweyan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Kecamatan Pasar Kliwon', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Kecamatan Serengan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Badan Pendapatan Daerah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Dinas Kebudayaan Dan Pariwisata', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'Badan Riset dan Inovasi Daerah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_opd' => 'RSUD Bung Karno', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}