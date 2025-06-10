{{-- File: resources/views/tabel_indikator_lengkap.blade.php (Anda bisa menamai file ini sesuai keinginan Anda) --}}

@extends('layouts.app')

{{-- Deklarasi Variabel Data Dummy di dalam Blade untuk kemudahan copy-paste --}}
@php
    $indikatorTitle = "Data Indikator Kependudukan (Contoh Lengkap)";
    $dimensiHeader = "Jenis Agama"; // Anda bisa ganti ini, contoh: "Status Pendidikan", "Jenis Kelamin"
    $targetYears = [2020, 2021, 2022];

    $structuredData = [
        'Kecamatan A' => [
            'kelurahan' => [
                'Kelurahan Melati' => [
                    'dimensi' => [
                        'Islam' => [
                            2020 => 1250,
                            2021 => 1300,
                            2022 => 1350,
                        ],
                        'Kristen Protestan' => [
                            2020 => 210,
                            2021 => 215,
                            2022 => 220,
                        ],
                        'Kristen Katolik' => [
                            2020 => 80,
                            2021 => 82,
                            2022 => 85,
                        ],
                    ],
                ],
                'Kelurahan Mawar' => [
                    'dimensi' => [
                        'Islam' => [
                            2020 => 980,
                            2021 => 1000,
                            2022 => 1020,
                        ],
                        'Kristen Protestan' => [
                            2020 => 150,
                            2021 => 155,
                            2022 => 160,
                        ],
                        'Hindu' => [ // Menambahkan dimensi lain untuk contoh
                            2020 => 20,
                            2021 => 25,
                            2022 => 30,
                        ],
                    ],
                ],
            ],
        ],
        'Kecamatan B' => [
            'kelurahan' => [
                'Kelurahan Anggrek' => [
                    'dimensi' => [
                        'Islam' => [
                            2020 => 1800,
                            2021 => 1850,
                            2022 => 1900,
                        ],
                        'Kristen Protestan' => [
                            2020 => 350,
                            2021 => 360,
                            2022 => 370,
                        ],
                        'Buddha' => [
                            2020 => 20,
                            2021 => 21,
                            2022 => 22,
                        ],
                    ],
                ],
            ],
        ],
    ];

    // Logika perhitungan rowspan untuk Kelurahan (tetap di sini)
    $kelurahanRowspanData = [];
    foreach($structuredData as $kecamatanName => $kecamatanInfo) {
        foreach($kecamatanInfo['kelurahan'] as $kelurahanName => $kelurahanInfo) {
            $kelurahanRowspanData[$kelurahanName] = count($kelurahanInfo['dimensi']);
        }
    }
    $currentKelurahan = null; // Variabel untuk melacak kelurahan yang sedang diproses
@endphp

@section('title', $indikatorTitle)

{{-- Asumsi Anda memiliki komponen x-navbar --}}
<x-navbar>
<section class="min-h-screen overflow-y-auto bg-white p-4">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-lg font-bold mb-4 text-center">
            {{ $indikatorTitle }}
        </h2>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm text-center rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-blue-200 text-blue-900 font-semibold">
                        <th rowspan="2" class="border border-gray-200 px-3 py-2">Kelurahan</th>
                        <th rowspan="2" class="border border-gray-200 px-3 py-2">{{ $dimensiHeader }}</th>
                        <th colspan="{{ count($targetYears) }}" class="border border-gray-200 px-3 py-2">Tahun</th>
                    </tr>
                    <tr class="bg-blue-500 text-white font-semibold">
                        @foreach($targetYears as $year)
                            <th class="border border-gray-200 px-3 py-2">{{ $year }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($structuredData as $kecamatanName => $kecamatanInfo)
                        @foreach($kecamatanInfo['kelurahan'] as $kelurahanName => $kelurahanInfo)
                            @foreach($kelurahanInfo['dimensi'] as $dimensiName => $dimensiValues)
                                <tr class="{{ $loop->parent->parent->iteration % 2 === 0 ? 'bg-white' : 'bg-blue-50' }}">
                                    {{-- Kolom Kelurahan --}}
                                    @if($currentKelurahan !== $kelurahanName)
                                        <td rowspan="{{ $kelurahanRowspanData[$kelurahanName] }}" class="border border-gray-200 px-3 py-2 font-medium text-gray-800">
                                            {{ $kelurahanName }}
                                        </td>
                                        @php $currentKelurahan = $kelurahanName; @endphp
                                    @endif

                                    {{-- Kolom Dimensi --}}
                                    <td class="border border-gray-200 px-3 py-2 text-gray-700">{{ $dimensiName }}</td>

                                    {{-- Kolom Nilai Tahun --}}
                                    @foreach($targetYears as $year)
                                        <td class="border border-gray-200 px-3 py-2">
                                            {{ $dimensiValues[$year] ?? 'N/A' }} {{-- Tampilkan nilai atau 'N/A' jika kosong --}}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            @php $currentKelurahan = null; @endphp {{-- Reset untuk kelurahan berikutnya --}}
                        @endforeach
                    @endforeach

                    @if(empty($structuredData))
                        <tr>
                            <td colspan="{{ 2 + count($targetYears) }}" class="border border-gray-200 px-3 py-2 text-center text-gray-700">
                                Tidak ada data yang tersedia untuk indikator ini.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</section>
</x-navbar>