@extends('layouts.app')

@section('title', $indikatorTitle)

<x-navbar>
<section class="min-h-screen overflow-y-auto bg-white p-4">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-3">{{ $indikatorTitle }}</h2>

        {{-- Form Filter --}}
        <div class="flex">
            <form id="filter-form" class="flex flex-wrap items-center justify-center gap-6 py-2">
                {{-- Filter Tahun --}}
                <div class="flex items-center gap-2 p-4 bg-white transition duration-200 ease-in-out">
                    <label for="year-select" class="font-semibold text-gray-700 text-sm">Tahun:</label>
                    <select name="year" id="year-select" class="block w-full min-w-[120px] border border-gray-300 rounded-lg py-2 px-3 text-sm text-gray-800 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FE482B] focus:border-transparent transition duration-200 ease-in-out cursor-pointer appearance-none bg-white pr-8">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $year == $tahunAnalisis ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
        
                {{-- Filter Kecamatan (Hanya untuk OPD) --}}
                @if(Auth::check() && Auth::user()->role === 'opd')
                <div class="flex items-center gap-2 p-4 bg-white transition duration-200 ease-in-out">
                    <label for="kecamatan-select" class="font-semibold text-gray-700 text-sm">Kecamatan:</label>
                    <select name="kecamatan" id="kecamatan-select" class="block w-full min-w-[180px] border border-gray-300 rounded-lg py-2 px-3 text-sm text-gray-800 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FE482B] focus:border-transparent transition duration-200 ease-in-out cursor-pointer appearance-none bg-white pr-8">
                        <option value="0">Semua Kecamatan</option>
                        @foreach($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->id }}" {{ $kecamatan->id == $selectedKecamatanId ? 'selected' : '' }}>
                                {{ $kecamatan->kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                {{-- Filter Kelurahan (Hanya untuk OPD dan Kecamatan) --}}
                @if(Auth::check() && in_array(Auth::user()->role, ['opd', 'kecamatan']))
                <div class="flex items-center gap-2 p-4 bg-white transition duration-200 ease-in-out">
                    <label for="kelurahan-select" class="font-semibold text-gray-700 text-sm">Kelurahan:</label>
                    <select name="kelurahan" id="kelurahan-select" class="block w-full min-w-[180px] border border-gray-300 rounded-lg py-2 px-3 text-sm text-gray-800 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FE482B] focus:border-transparent transition duration-200 ease-in-out cursor-pointer appearance-none bg-white pr-8">
                        <option value="0">Semua Kelurahan</option>
                        @foreach($kelurahans as $kelurahan)
                            <option value="{{ $kelurahan->id }}" {{ $kelurahan->id == $selectedKelurahanId ? 'selected' : '' }}>
                                {{ $kelurahan->kelurahan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="overflow-x-auto flex justify-center shadow-lg">
           <table class="min-w-full border-collapse border border-gray-200 text-sm text-center overflow-hidden">
                <thead class="bg-[#FE482B] text-white">
                    <tr>
                        @if(Auth::check() && Auth::user()->role === 'opd')
                            <th rowspan="2" class="border ...">Kecamatan</th>
                            <th rowspan="2" class="border ...">Kelurahan</th>
                        @elseif(Auth::check() && Auth::user()->role === 'kecamatan')
                            <th rowspan="2" class="border ...">Kelurahan</th>
                        @endif
                        
                        {{-- Header Kolom Dimensi menjadi dinamis --}}
                        <th rowspan="2" class="border border-gray-200 px-4 py-3 font-semibold">{{ $dimensiHeader }}</th>
                        
                        <th colspan="2" class="border ...">Laki-laki</th>
                        <th colspan="2" class="border ...">Perempuan</th>
                        <th rowspan="2" class="border ...">Jumlah</th>
                    </tr>
                    <tr class="bg-[#e03d25] text-white">
                        <th class="border ...">n</th><th class="border ...">%</th><th class="border ...">n</th><th class="border ...">%</th>
                    </tr>
                </thead>
                <tbody>
                    @php $currentKecamatan = null; $currentKelurahan = null; @endphp
                    @forelse ($structuredData as $kecamatanName => $kecamatanInfo)
                        @foreach ($kecamatanInfo['kelurahan'] as $kelurahanName => $kelurahanInfo)
                            
                            {{-- PERUBAHAN UTAMA DI SINI --}}
                            {{-- Loop menggunakan $dimensionKey yang dikirim dari controller --}}
                            @foreach ($kelurahanInfo[$dimensionKey] as $dimensiName => $values)
                                <tr class="{{ $loop->parent->parent->iteration % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 ...">
                                    {{-- Kolom adaptif Kecamatan & Kelurahan (tidak berubah) --}}
                                    @if(Auth::check() && Auth::user()->role === 'opd')
                                        @if ($currentKecamatan !== $kecamatanName)<td rowspan="{{ $kecamatanInfo['rowspan'] }}" class="border ...">{{ $kecamatanName }}</td>@php $currentKecamatan = $kecamatanName; @endphp @endif
                                    @endif
                                    @if(Auth::check() && in_array(Auth::user()->role, ['opd', 'kecamatan']))
                                        @if ($currentKelurahan !== $kelurahanName)<td rowspan="{{ $kelurahanInfo['rowspan'] }}" class="border ...">{{ $kelurahanName }}</td>@php $currentKelurahan = $kelurahanName; @endphp @endif
                                    @endif

                                    {{-- Variabel $dimensiName menjadi generik --}}
                                    <td class="border border-gray-200 px-4 py-2 text-left">{{ $dimensiName }}</td>
                                    
                                    {{-- Kolom nilai (tidak berubah) --}}
                                    <td class="border ...">{{ number_format($values['laki_n'], 0, ',', '.') }}</td>
                                    <td class="border ...">{{ number_format($values['laki_pct'], 2, ',', '.') }}%</td>
                                    <td class="border ...">{{ number_format($values['perempuan_n'], 0, ',', '.') }}</td>
                                    <td class="border ...">{{ number_format($values['perempuan_pct'], 2, ',', '.') }}%</td>
                                    <td class="border ... font-semibold">{{ number_format($values['jumlah'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            @php $currentKelurahan = null; @endphp
                        @endforeach
                        @php $currentKecamatan = null; @endphp
                    @empty
                       @php
                            $colspan = 6;
                            if (Auth::check() && in_array(Auth::user()->role, ['opd', 'kecamatan'])) $colspan++;
                            if (Auth::check() && Auth::user()->role === 'opd') $colspan++;
                        @endphp
                        <tr><td colspan="{{ $colspan }}" class="border border-gray-200 px-4 py-4 text-center text-gray-500">Tidak ada data yang tersedia untuk filter yang dipilih.</td></tr>
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
        
        // Script ini secara otomatis menggunakan route dari halaman saat ini, jadi bisa dipakai di mana saja.
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