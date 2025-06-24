@extends('layouts.app')

@section('title', $indikator->nama_indikator)

<x-navbar>
<section class="min-h-screen overflow-y-auto bg-white p-4">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">{{ $indikator->nama_indikator }}</h2>

        {{-- Wadah untuk Filter dan Tombol --}}
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
            {{-- Bagian Kiri: Form Filter (Ditampilkan berdasarkan role) --}}
            @if (Auth::user()->role !== 'kelurahan')
            <form id="filter-form" method="GET" action="{{ route('laporan.pegawai_usia', ['indikatorId' => $indikator->id]) }}" class="flex flex-wrap items-center gap-6">
                @if (Auth::user()->role === 'opd')
                <div class="flex items-center gap-2">
                    <label for="kecamatan-select" class="font-semibold text-gray-700 text-sm">Kecamatan:</label>
                    <select name="kecamatan" id="kecamatan-select" class="border border-gray-300 rounded-lg py-2 px-3">
                        <option value="">Semua Kecamatan</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->kecamatan }}" {{ $kec->kecamatan == $selectedKecamatan ? 'selected' : '' }}>{{ $kec->kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if (in_array(Auth::user()->role, ['opd', 'kecamatan']))
                <div class="flex items-center gap-2">
                    <label for="kelurahan-select" class="font-semibold text-gray-700 text-sm">Kelurahan:</label>
                    <select name="kelurahan" id="kelurahan-select" class="border border-gray-300 rounded-lg py-2 px-3">
                        <option value="">Semua Kelurahan</option>
                        @foreach($kelurahans as $kel)
                            <option value="{{ $kel->kelurahan }}" {{ $kel->kelurahan == $selectedKelurahan ? 'selected' : '' }}>{{ $kel->kelurahan }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
            @else
                <div class="flex-grow"></div>
            @endif

            {{-- Bagian Kanan: Tombol Unduh --}}
            <a href="{{ route('laporan.export.pegawai_usia', ['indikatorId' => $indikator->id, 'kecamatan' => $selectedKecamatan ?? '', 'kelurahan' => $selectedKelurahan ?? '']) }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center transition duration-200 whitespace-nowrap">
                <i class="fas fa-download mr-2"></i> Unduh Excel
            </a>
        </div>

        {{-- Tabel Data --}}
        <div class="overflow-x-auto flex justify-start shadow-lg">
            <table class="min-w-full border-collapse border border-gray-300 text-sm text-center">
                <thead class="bg-[#FE482B] text-white">
                    <tr>
                        @if (Auth::user()->role === 'opd')
                            <th rowspan="2" class="border p-3">Kecamatan</th>
                        @endif
                        @if (in_array(Auth::user()->role, ['opd', 'kecamatan']))
                            <th rowspan="2" class="border p-3">Kelurahan</th>
                        @endif
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
                        @foreach ($kecInfo['kelurahans'] as $kelName => $kelInfo)
                            @foreach ($kelInfo['usias'] as $usiaName => $usiaInfo)
                                @foreach ($usiaInfo['rows'] as $row)
                                    <tr class="hover:bg-gray-50">
                                        @if (Auth::user()->role === 'opd')
                                            @if($currentKec !== $kecName)
                                                <td rowspan="{{ $kecInfo['rowspan_kecamatan'] }}" class="border p-2 align-middle text-center font-bold">{{ $kecName }}</td>
                                                @php $currentKec = $kecName; @endphp
                                            @endif
                                        @endif
                                        @if (in_array(Auth::user()->role, ['opd', 'kecamatan']))
                                            @if($currentKel !== $kelName)
                                                <td rowspan="{{ $kelInfo['rowspan_kelurahan'] }}" class="border p-2 align-middle text-center">{{ $kelName }}</td>
                                                @php $currentKel = $kelName; @endphp
                                            @endif
                                        @endif
                                        @if($currentUsia !== $usiaName)
                                            <td rowspan="{{ $usiaInfo['rowspan_usia'] }}" class="border p-2 align-middle text-left">{{ $usiaName }}</td>
                                            @php $currentUsia = $usiaName; @endphp
                                        @endif
                                        <td class="border p-2 text-left">{{ $row['jenis_kelamin'] }}</td>
                                        @foreach ($uniqueYearsInView as $year)
                                            <td class="border p-2 text-right">{{ number_format($row['yearly_data'][$year]['ASN'], 0, ',', '.') }}</td>
                                            <td class="border p-2 text-right">{{ number_format($row['yearly_data'][$year]['Non ASN'], 0, ',', '.') }}</td>
                                            <td class="border p-2 bg-gray-100 font-semibold text-right">{{ number_format($row['yearly_data'][$year]['Total'], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    @empty
                        @php
                            $colspan = 4 + (count($uniqueYearsInView) * 3);
                        @endphp
                        <tr><td colspan="{{ $colspan }}" class="p-4 text-center">Data tidak ditemukan untuk filter ini.</td></tr>
                    @endforelse
                    @if(count($structuredData) > 0)
                        @php
                            $colspanForTotal = 2;
                            if (in_array(Auth::user()->role, ['opd', 'kecamatan'])) $colspanForTotal++;
                            if (Auth::user()->role === 'opd') $colspanForTotal++;
                        @endphp
                        <tr class="bg-gray-200 font-bold">
                            <td colspan="{{ $colspanForTotal }}" class="border p-2 text-right">Jumlah</td>
                            @foreach ($uniqueYearsInView as $year)
                                <td class="text-right border p-2">{{ number_format($grandTotalsPerYear[$year]['ASN'], 0, ',', '.') }}</td>
                                <td class="text-right border p-2">{{ number_format($grandTotalsPerYear[$year]['Non ASN'], 0, ',', '.') }}</td>
                                <td class="text-right border p-2 bg-gray-300">{{ number_format($grandTotalsPerYear[$year]['Total'], 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        // Hapus atribut onchange dari setiap select di dalam form
        const selects = filterForm.getElementsByTagName('select');
        for (let select of selects) {
            select.removeAttribute('onchange');
        }
        // Tambahkan event listener ke form
        filterForm.addEventListener('change', function() {
            this.submit();
        });
    }
</script>
</x-navbar>