@extends('layouts.app')

@section('title', $indikator->nama_indikator)

<x-navbar>
<section class="min-h-screen overflow-y-auto bg-white p-4">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">{{ $indikator->nama_indikator }}</h2>

        {{-- Filter --}}
        <div class="flex justify-start mb-6">
            <form id="filter-form" method="GET" action="{{ route('laporan.pegawai_usia', ['indikatorId' => $indikator->id]) }}" class="flex flex-wrap items-center gap-6">
                {{-- Filter Kecamatan --}}
                <div class="flex items-center gap-2">
                    <label for="kecamatan-select" class="font-semibold text-gray-700 text-sm">Kecamatan:</label>
                    <select name="kecamatan" id="kecamatan-select" onchange="this.form.submit()" class="border border-gray-300 rounded-lg py-2 px-3">
                        <option value="">Semua Kecamatan</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->kecamatan }}" {{ $kec->kecamatan == $selectedKecamatan ? 'selected' : '' }}>{{ $kec->kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Filter Kelurahan --}}
                <div class="flex items-center gap-2">
                    <label for="kelurahan-select" class="font-semibold text-gray-700 text-sm">Kelurahan:</label>
                    <select name="kelurahan" id="kelurahan-select" onchange="this.form.submit()" class="border border-gray-300 rounded-lg py-2 px-3">
                        <option value="">Semua Kelurahan</option>
                        @foreach($kelurahans as $kel)
                            <option value="{{ $kel->kelurahan }}" {{ $kel->kelurahan == $selectedKelurahan ? 'selected' : '' }}>{{ $kel->kelurahan }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto flex justify-start shadow-lg rounded-lg">
            <table class="min-w-full border-collapse border border-gray-300 text-sm text-center">
                <thead class="bg-[#FE482B] text-white">
                    <tr>
                        <th rowspan="2" class="border p-3">Kecamatan</th>
                        <th rowspan="2" class="border p-3">Kelurahan</th>
                        <th rowspan="2" class="border p-3">Usia</th>
                        <th rowspan="2" class="border p-3">Jenis Kelamin</th>
                        @foreach ($uniqueYearsInView as $year)
                            <th colspan="3" class="border p-3">{{ $year }}</th>
                        @endforeach
                    </tr>
                    <tr class="bg-[#e03d25] text-white">
                        @foreach ($uniqueYearsInView as $year)
                            <th class="border p-2">ASN</th>
                            <th class="border p-2">Non ASN</th>
                            <th class="border p-2">Total</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php $currentKec = ''; $currentKel = ''; $currentUsia = ''; @endphp

                    @forelse ($structuredData as $kecName => $kecInfo)
                        {{-- Loop untuk setiap Kecamatan --}}
                        @foreach ($kecInfo['kelurahans'] as $kelName => $kelInfo)
                            {{-- Loop untuk setiap Kelurahan --}}
                            @foreach ($kelInfo['usias'] as $usiaName => $usiaInfo)
                                {{-- Loop untuk setiap baris data (Laki-laki/Perempuan) --}}
                                @foreach ($usiaInfo['rows'] as $row)
                                    <tr class="hover:bg-gray-50">
                                        {{-- Logika rowspan sekarang membaca struktur data yang benar --}}
                                        @if($currentKec !== $kecName)
                                            <td rowspan="{{ $kecInfo['rowspan_kecamatan'] }}" class="border p-2 align-middle text-center font-bold">{{ $kecName }}</td>
                                            @php $currentKec = $kecName; @endphp
                                        @endif

                                        @if($currentKel !== $kelName)
                                            <td rowspan="{{ $kelInfo['rowspan_kelurahan'] }}" class="border p-2 align-middle text-center">{{ $kelName }}</td>
                                            @php $currentKel = $kelName; @endphp
                                        @endif

                                        @if($currentUsia !== $usiaName)
                                            <td rowspan="{{ $usiaInfo['rowspan_usia'] }}" class="border p-2 align-middle text-left">{{ $usiaName }}</td>
                                            @php $currentUsia = $usiaName; @endphp
                                        @endif
                                        
                                        <td class="border p-2 text-left">{{ $row['jenis_kelamin'] }}</td>

                                        @foreach ($uniqueYearsInView as $year)
                                            <td class="border p-2 text-right">{{ number_format($row['yearly_data'][$year]['ASN']) }}</td>
                                            <td class="border p-2 text-right">{{ number_format($row['yearly_data'][$year]['Non ASN']) }}</td>
                                            <td class="border p-2 bg-gray-100 font-semibold text-right">{{ number_format($row['yearly_data'][$year]['Total']) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    @empty
                        <tr><td colspan="{{ 4 + (count($uniqueYearsInView) * 3) }}" class="p-4 text-center">Data tidak ditemukan untuk filter ini.</td></tr>
                    @endforelse

                    {{-- Baris Grand Total (Tidak ada perubahan) --}}
                    @if(count($structuredData) > 0)
                        <tr class="bg-gray-200 font-bold">
                            <td colspan="4" class="border p-2 text-right">Jumlah</td>
                            @foreach ($uniqueYearsInView as $year)
                                <td class="text-right border p-2">{{ number_format($grandTotalsPerYear[$year]['ASN']) }}</td>
                                <td class="text-right border p-2">{{ number_format($grandTotalsPerYear[$year]['Non ASN']) }}</td>
                                <td class="text-right border p-2 bg-gray-300">{{ number_format($grandTotalsPerYear[$year]['Total']) }}</td>
                            @endforeach
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</section>
</x-navbar>