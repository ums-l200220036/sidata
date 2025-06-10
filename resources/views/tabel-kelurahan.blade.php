{{-- File: resources/views/tabel_indikator_hanya_agama.blade.php (Anda bisa menamai file ini sesuai keinginan Anda) --}}

@extends('layouts.app')

{{-- Deklarasi Variabel Data Dummy dan Transformasi Data di dalam Blade --}}
@php
    $indikatorTitle = "Data Kependudukan Berdasarkan Jenis Agama (Ringkas)";
    $dimensiHeader = "Jenis Agama"; // Ini akan menjadi kolom pertama
    $targetYears = [2020, 2021, 2022];

    // Data asli (tetap dalam struktur Kecamatan -> Kelurahan -> Dimensi)
    $originalStructuredData = [
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
                        'Hindu' => [
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
                        'Hindu' => [
                            2020 => 40,
                            2021 => 42,
                            2022 => 45,
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

    // --- Transformasi Data: Agregasi langsung berdasarkan Dimensi ---
    // Di sini kita akan menjumlahkan semua nilai per dimensi untuk setiap tahun
    $aggregatedData = [];
    foreach($originalStructuredData as $kecamatanName => $kecamatanInfo) {
        foreach($kecamatanInfo['kelurahan'] as $kelurahanName => $kelurahanInfo) {
            foreach($kelurahanInfo['dimensi'] as $dimensiName => $dimensiValues) {
                if (!isset($aggregatedData[$dimensiName])) {
                    $aggregatedData[$dimensiName] = [];
                    // Inisialisasi semua tahun dengan 0 untuk dimensi baru
                    foreach($targetYears as $year) {
                        $aggregatedData[$dimensiName][$year] = 0;
                    }
                }
                foreach($targetYears as $year) {
                    $aggregatedData[$dimensiName][$year] += $dimensiValues[$year] ?? 0;
                }
            }
        }
    }

    // Karena tidak ada lagi rowspan, variabel $currentDimensi tidak diperlukan untuk tampilan ini
    // $currentDimensi = null;
@endphp

@section('title', $indikatorTitle)

{{-- Asumsi Anda memiliki komponen x-navbar --}}
<x-navbar>
<section class="min-h-screen overflow-y-auto bg-white p-4">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-lg font-bold mb-4 text-center">
            {{ $indikatorTitle }}
        </h2>

        <div class="overflow-x-auto flex justify-center items-center">
            <table class="w-3/4 border border-gray-200 text-sm text-center rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-blue-200 text-blue-900 font-semibold">
                        <th rowspan="2" class="border border-gray-200 px-3 py-2">{{ $dimensiHeader }}</th> {{-- Kolom Dimensi --}}
                        <th colspan="{{ count($targetYears) }}" class="border border-gray-200 px-3 py-2">Tahun</th>
                    </tr>
                    <tr class="bg-blue-500 text-white font-semibold">
                        @foreach($targetYears as $year)
                            <th class="border border-gray-200 px-3 py-2">{{ $year }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($aggregatedData as $dimensiName => $dimensiValues)
                        <tr class="{{ $loop->iteration % 2 === 0 ? 'bg-white' : 'bg-blue-50' }}">
                            {{-- Kolom Dimensi --}}
                            <td class="border border-gray-200 px-3 py-2 font-medium text-gray-800">
                                {{ $dimensiName }}
                            </td>

                            {{-- Kolom Nilai Tahun --}}
                            @foreach($targetYears as $year)
                                <td class="border border-gray-200 px-3 py-2">
                                    {{ $dimensiValues[$year] ?? 'N/A' }}
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 1 + count($targetYears) }}" class="border border-gray-200 px-3 py-2 text-center text-gray-700">
                                Tidak ada data yang tersedia untuk indikator ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
</x-navbar>