@extends('layouts.app')


@section('title', $indikatorTitle)

<x-navbar>
<section class="min-h-screen overflow-y-auto bg-white p-4">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-lg font-bold mb-4 text-center">
            {{ $indikatorTitle }}
        </h2>

        <div class="overflow-x-auto flex justify-center items-center">
            <table class="min-w-full border border-gray-200 text-sm text-center rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-blue-200 text-blue-900 font-semibold">
                        <th rowspan="2" class="border border-gray-200 px-3 py-2">Kecamatan</th> {{-- Tambahkan kolom Kecamatan --}}
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
                    @php
                        $currentKecamatan = null;
                        $currentKelurahan = null;
                    @endphp

                    @forelse($structuredData as $kecamatanName => $kecamatanInfo)
                        @foreach($kecamatanInfo['kelurahan'] as $kelurahanName => $kelurahanInfo)
                            @foreach($kelurahanInfo['dimensi'] as $dimensiName => $dimensiValues)
                                <tr class="{{ $loop->parent->parent->iteration % 2 === 0 ? 'bg-white' : 'bg-blue-50' }}">

                                    {{-- Kolom Kecamatan --}}
                                    @if($currentKecamatan !== $kecamatanName)
                                        <td rowspan="{{ $kecamatanInfo['rowspan'] }}" class="border border-gray-200 px-3 py-2 font-bold text-gray-900 bg-blue-100 align-middle">
                                            {{ $kecamatanName }}
                                        </td>
                                        @php $currentKecamatan = $kecamatanName; @endphp
                                    @endif

                                    {{-- Kolom Kelurahan --}}
                                    @if($currentKelurahan !== $kelurahanName)
                                        {{-- PERBAIKAN: Gunakan 'rowspan' agar konsisten dengan Controller --}}
                                        <td rowspan="{{ $kelurahanInfo['rowspan'] }}" class="border border-gray-200 px-3 py-2 font-medium text-gray-800 align-middle">
                                            {{ $kelurahanName }}
                                        </td>
                                        @php $currentKelurahan = $kelurahanName; @endphp
                                    @endif

                                    {{-- Kolom Dimensi --}}
                                    <td class="border border-gray-200 px-3 py-2 text-gray-700">{{ $dimensiName }}</td>

                                    {{-- Kolom Nilai Tahun --}}
                                    @foreach($targetYears as $year)
                                        <td class="border border-gray-200 px-3 py-2">
                                            {{ isset($dimensiValues[$year]) ? (int)$dimensiValues[$year] : 'N/A' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            @php $currentKelurahan = null; @endphp
                        @endforeach
                        @php $currentKecamatan = null; @endphp
                    @empty
                        <tr>
                            <td colspan="{{ 3 + count($targetYears) }}" class="border border-gray-200 px-3 py-2 text-center text-gray-700">
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