@extends('layouts.app')

@section('title', $indikatorTitle)

<x-navbar>
<section class="min-h-screen overflow-y-auto bg-white p-4">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-lg font-bold mb-4 text-center">{{ $indikatorTitle }}</h2>

        {{-- Form Filter (Tidak ada perubahan) --}}
        <div class="flex justify-center mb-6">
            <form id="filter-form" class="flex flex-wrap items-center justify-center gap-4 bg-gray-50 p-3 rounded-lg border">
                <div class="flex items-center gap-2">
                    <label for="year-select" class="font-semibold text-gray-700">Tahun:</label>
                    <select name="year" id="year-select" class="border border-gray-300 rounded-md p-2">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $year == $tahunAnalisis ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                @if(Auth::user()->role === 'opd')
                <div class="flex items-center gap-2">
                    <label for="kecamatan-select" class="font-semibold text-gray-700">Kecamatan:</label>
                    <select name="kecamatan" id="kecamatan-select" class="border border-gray-300 rounded-md p-2">
                        <option value="0">Semua Kecamatan</option>
                        @foreach($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->id }}" {{ $kecamatan->id == $selectedKecamatanId ? 'selected' : '' }}>{{ $kecamatan->kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if(in_array(Auth::user()->role, ['opd', 'kecamatan']))
                <div class="flex items-center gap-2">
                    <label for="kelurahan-select" class="font-semibold text-gray-700">Kelurahan:</label>
                    <select name="kelurahan" id="kelurahan-select" class="border border-gray-300 rounded-md p-2">
                        <option value="0">Semua Kelurahan</option>
                        @foreach($kelurahans as $kelurahan)
                            <option value="{{ $kelurahan->id }}" {{ $kelurahan->id == $selectedKelurahanId ? 'selected' : '' }}>{{ $kelurahan->kelurahan }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto flex justify-center">
           <table class="min-w-full border border-gray-200 text-sm text-center rounded-lg overflow-hidden">
                <thead class="bg-blue-200 text-blue-900 font-semibold">
                    <tr>
                        {{-- Header Kolom Adaptif --}}
                        @if(Auth::user()->role === 'opd')
                            <th rowspan="2" class="border border-gray-200 px-3 py-2">Kecamatan</th>
                            <th rowspan="2" class="border border-gray-200 px-3 py-2">Kelurahan</th>
                        @elseif(Auth::user()->role === 'kecamatan')
                            <th rowspan="2" class="border border-gray-200 px-3 py-2">Kelurahan</th>
                        @endif
                        {{-- Perubahan Teks Header --}}
                        <th rowspan="2" class="border border-gray-200 px-3 py-2">Pekerjaan</th>
                        <th colspan="2" class="border border-gray-200 px-3 py-2">Laki-laki</th>
                        <th colspan="2" class="border border-gray-200 px-3 py-2">Perempuan</th>
                        <th rowspan="2" class="border border-gray-200 px-3 py-2">Jumlah</th>
                    </tr>
                    <tr class="bg-blue-500 text-white">
                        <th class="border border-gray-200 px-3 py-2">n</th><th class="border border-gray-200 px-3 py-2">%</th><th class="border border-gray-200 px-3 py-2">n</th><th class="border border-gray-200 px-3 py-2">%</th>
                    </tr>
                </thead>
                <tbody>
                    @php $currentKecamatan = null; $currentKelurahan = null; @endphp
                    @forelse ($structuredData as $kecamatanName => $kecamatanInfo)
                        @foreach ($kecamatanInfo['kelurahan'] as $kelurahanName => $kelurahanInfo)
                            {{-- Perubahan Variabel Loop --}}
                            @foreach ($kelurahanInfo['pekerjaan'] as $pekerjaanName => $values)
                                <tr class="{{ $loop->parent->parent->iteration % 2 === 0 ? 'bg-white' : 'bg-blue-50' }}">
                                    {{-- Kolom Data Adaptif --}}
                                    @if(Auth::user()->role === 'opd')
                                        @if ($currentKecamatan !== $kecamatanName)<td rowspan="{{ $kecamatanInfo['rowspan'] }}" class="border border-gray-200 px-3 py-2 font-bold align-middle text-center">{{ $kecamatanName }}</td>@php $currentKecamatan = $kecamatanName; @endphp @endif
                                    @endif
                                    @if(in_array(Auth::user()->role, ['opd', 'kecamatan']))
                                        @if ($currentKelurahan !== $kelurahanName)<td rowspan="{{ $kelurahanInfo['rowspan'] }}" class="border border-gray-200 px-3 py-2 font-medium align-middle text-center">{{ $kelurahanName }}</td>@php $currentKelurahan = $kelurahanName; @endphp @endif
                                    @endif
                                    {{-- Perubahan Nama Variabel --}}
                                    <td class="border border-gray-200 px-3 py-2 text-left">{{ $pekerjaanName }}</td>
                                    <td class="border border-gray-200 px-3 py-2">{{ number_format($values['laki_n'], 0, ',', '.') }}</td>
                                    <td class="border border-gray-200 px-3 py-2">{{ number_format($values['laki_pct'], 2, ',', '.') }}%</td>
                                    <td class="border border-gray-200 px-3 py-2">{{ number_format($values['perempuan_n'], 0, ',', '.') }}</td>
                                    <td class="border border-gray-200 px-3 py-2">{{ number_format($values['perempuan_pct'], 2, ',', '.') }}%</td>
                                    <td class="border border-gray-200 px-3 py-2 font-semibold">{{ number_format($values['jumlah'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            @php $currentKelurahan = null; @endphp
                        @endforeach
                        @php $currentKecamatan = null; @endphp
                    @empty
                        @php
                            $colspan = 6;
                            if (in_array(Auth::user()->role, ['opd', 'kecamatan'])) $colspan++;
                            if (Auth::user()->role === 'opd') $colspan++;
                        @endphp
                        <tr><td colspan="{{ $colspan }}" class="border border-gray-200 px-3 py-2 text-center text-gray-500">Tidak ada data yang tersedia untuk filter yang dipilih.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- Script Filter (Tidak ada perubahan) --}}
<script>
    document.getElementById('filter-form').addEventListener('change', function(e) {
        const tahun = document.getElementById('year-select').value;
        const kecamatanSelect = document.getElementById('kecamatan-select');
        const kelurahanSelect = document.getElementById('kelurahan-select');
        const kecamatanId = kecamatanSelect ? kecamatanSelect.value : '0';
        const kelurahanId = kelurahanSelect ? kelurahanSelect.value : '0';
        const baseUrl = "{{ route(request()->route()->getName(), ['indikatorId' => $indikator->id]) }}";
        let url = `${baseUrl}/${tahun}`;
        if (kelurahanId !== '0') {
            url += `/0/${kelurahanId}`;
        } else if (kecamatanId !== '0') {
            url += `/${kecamatanId}`;
        }
        window.location.href = url;
    });
</script>
</x-navbar>