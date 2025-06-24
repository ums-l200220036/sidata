@extends('layouts.app')

@section('title', $indikatorTitle)

<x-navbar>
<section class="min-h-screen overflow-y-auto bg-white p-4">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">{{ $indikatorTitle }}</h2>

        {{-- Wadah untuk Filter dan Tombol (Kode ini sudah benar) --}}
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
            @if (Auth::user()->role !== 'kelurahan')
            <form id="filter-form" class="flex flex-wrap items-center gap-x-6 gap-y-2">
                @if (Auth::user()->role === 'opd')
                <div class="flex items-center">
                    <label for="kecamatan-select" class="font-semibold text-gray-700 text-sm mr-2">Kecamatan:</label>
                    <select name="kecamatan" id="kecamatan-select" class="block w-full min-w-[180px] border border-gray-300 rounded-lg py-2 px-3 text-sm text-gray-800 cursor-pointer">
                        <option value="0">Semua Kecamatan</option>
                        @foreach($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->id }}" {{ $kecamatan->id == $selectedKecamatanId ? 'selected' : '' }}>{{ $kecamatan->kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if (in_array(Auth::user()->role, ['opd', 'kecamatan']))
                <div class="flex items-center">
                    <label for="kelurahan-select" class="font-semibold text-gray-700 text-sm mr-2">Kelurahan:</label>
                    <select name="kelurahan" id="kelurahan-select" class="block w-full min-w-[180px] border border-gray-300 rounded-lg py-2 px-3 text-sm text-gray-800 cursor-pointer">
                        <option value="0">Semua Kelurahan</option>
                        @foreach($kelurahans as $kelurahan)
                            <option value="{{ $kelurahan->id }}" data-kecamatan-id="{{ $kelurahan->parent_id }}" {{ $kelurahan->id == $selectedKelurahanId ? 'selected' : '' }}>{{ $kelurahan->kelurahan }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
            @else
                <div class="flex-grow"></div>
            @endif

            <a href="{{ route('laporan.export.prioritas', ['indikatorId' => $indikator->id, 'tahun' => $tahunAnalisis, 'kecamatanId' => $selectedKecamatanId ?? 0, 'kelurahanId' => $selectedKelurahanId ?? 0]) }}"
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                <i class="fas fa-download mr-2"></i> Unduh Excel
            </a>
        </div>

        {{-- Tabel Data --}}
        <div class="overflow-x-auto flex justify-center shadow-lg">
            <table class="min-w-full border-collapse border border-gray-300 text-sm">
                <thead class="bg-[#FE482B] text-white">
                    {{-- Bagian thead tidak perlu diubah, sudah benar --}}
                    <tr>
                        @if (Auth::user()->role === 'opd')
                        <th rowspan="2" class="border px-4 py-3 font-semibold text-center align-middle">Kecamatan</th>
                        @endif
                        @if (in_array(Auth::user()->role, ['opd', 'kecamatan']))
                        <th rowspan="2" class="border px-4 py-3 font-semibold text-center align-middle">Kelurahan</th>
                        @endif
                        <th rowspan="2" class="border px-4 py-3 font-semibold text-center align-middle">{{ $dimensiHeader }}</th>
                        <th colspan="2" class="border px-4 py-3 font-semibold text-center align-middle">Tahun ke {{$tahunSebelumnya}} Semester 1</th>
                        <th colspan="2" class="border px-4 py-3 font-semibold text-center align-middle">Tahun ke {{$tahunSebelumnya}} Semester 2</th>
                        <th colspan="2" class="border px-4 py-3 font-semibold text-center align-middle">Tahun ke {{$tahunAnalisis}} Semester 1</th>
                    </tr>
                    <tr class="bg-[#e03d25] text-white">
                        <th class="border px-4 py-2 text-center align-middle">Individu</th><th class="border px-4 py-2 text-center align-middle">Keluarga</th>
                        <th class="border px-4 py-2 text-center align-middle">Individu</th><th class="border px-4 py-2 text-center align-middle">Keluarga</th>
                        <th class="border px-4 py-2 text-center align-middle">Individu</th><th class="border px-4 py-2 text-center align-middle">Keluarga</th>
                    </tr>
                </thead>
                {{-- ============================================= --}}
                {{-- PERBAIKAN TOTAL LOGIKA TBODY DI SINI --}}
                {{-- ============================================= --}}
                <tbody>
                    @forelse ($structuredData as $kecamatanName => $kecamatanInfo)
                        @php $isFirstRowOfKecamatan = true; @endphp
                        @foreach ($kecamatanInfo['kelurahan'] as $kelurahanName => $kelurahanInfo)
                            @php $isFirstRowOfKelurahan = true; @endphp
                            @foreach ($kelurahanInfo['prioritas'] as $prioritasName => $values)
                                <tr class="hover:bg-gray-50">
                                    {{-- Render <td> Kecamatan hanya di baris paling pertama grupnya --}}
                                    @if (Auth::user()->role === 'opd' && $isFirstRowOfKecamatan)
                                        <td rowspan="{{ $kecamatanInfo['rowspan'] }}" class="border px-4 py-2 font-bold text-center align-middle">{{ $kecamatanName }}</td>
                                        @php $isFirstRowOfKecamatan = false; @endphp
                                    @endif

                                    {{-- Render <td> Kelurahan hanya di baris paling pertama grupnya --}}
                                    @if (in_array(Auth::user()->role, ['opd', 'kecamatan']) && $isFirstRowOfKelurahan)
                                        <td rowspan="{{ $kelurahanInfo['rowspan'] }}" class="border px-4 py-2 text-center align-middle">{{ $kelurahanName }}</td>
                                        @php $isFirstRowOfKelurahan = false; @endphp
                                    @endif
                                    
                                    {{-- Kolom yang selalu dirender --}}
                                    <td class="border px-4 py-2 text-left align-middle">{{ $prioritasName }}</td>
                                    <td class="border px-4 py-2 text-center align-middle">{{ number_format($values["{$tahunSebelumnya}_s1"]['individu'], 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-center align-middle">{{ number_format($values["{$tahunSebelumnya}_s1"]['keluarga'], 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-center align-middle">{{ number_format($values["{$tahunSebelumnya}_s2"]['individu'], 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-center align-middle">{{ number_format($values["{$tahunSebelumnya}_s2"]['keluarga'], 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-center align-middle">{{ number_format($values["{$tahunAnalisis}_s1"]['individu'], 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-center align-middle">{{ number_format($values["{$tahunAnalisis}_s1"]['keluarga'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            {{-- Baris Total --}}
                            <tr class="bg-gray-200 font-bold">
                                {{-- Render <td> Total di kolom pertama yang tersedia (kolom Prioritas) --}}
                                <td class="border px-4 py-2 text-left align-middle">Total</td>
                                @php $totalValues = $kelurahanInfo['total']; @endphp
                                <td class="border px-4 py-2 text-center align-middle">{{ number_format($totalValues["{$tahunSebelumnya}_s1"]['individu'], 0, ',', '.') }}</td>
                                <td class="border px-4 py-2 text-center align-middle">{{ number_format($totalValues["{$tahunSebelumnya}_s1"]['keluarga'], 0, ',', '.') }}</td>
                                <td class="border px-4 py-2 text-center align-middle">{{ number_format($totalValues["{$tahunSebelumnya}_s2"]['individu'], 0, ',', '.') }}</td>
                                <td class="border px-4 py-2 text-center align-middle">{{ number_format($totalValues["{$tahunSebelumnya}_s2"]['keluarga'], 0, ',', '.') }}</td>
                                <td class="border px-4 py-2 text-center align-middle">{{ number_format($totalValues["{$tahunAnalisis}_s1"]['individu'], 0, ',', '.') }}</td>
                                <td class="border px-4 py-2 text-center align-middle">{{ number_format($totalValues["{$tahunAnalisis}_s1"]['keluarga'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @empty
                        @php
                            $colspan = 7;
                            if(in_array(Auth::user()->role, ['opd', 'kecamatan'])) $colspan++;
                            if(Auth::user()->role === 'opd') $colspan++;
                        @endphp
                        <tr><td colspan="{{ $colspan }}" class="border px-4 py-4 text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    const filterForm = document.getElementById('filter-form');
    if(filterForm) {
        filterForm.addEventListener('change', function(e) {
            const tahun = {{ $tahunAnalisis }};
            const baseUrl = "{{ route('laporan.prioritas', ['indikatorId' => $indikator->id]) }}";
            
            const kecamatanSelect = document.getElementById('kecamatan-select');
            const kelurahanSelect = document.getElementById('kelurahan-select');

            let kecamatanId = kecamatanSelect ? kecamatanSelect.value : '0';
            let kelurahanId = kelurahanSelect ? kelurahanSelect.value : '0';
            
            if (e.target.id === 'kelurahan-select' && kelurahanId !== '0') {
                const selectedOption = kelurahanSelect.options[kelurahanSelect.selectedIndex];
                kecamatanId = selectedOption.dataset.kecamatanId || kecamatanId;
            }
            
            let url = `${baseUrl}/${tahun}`;
            if (kelurahanId !== '0') {
                url += `/${kecamatanId}/${kelurahanId}`;
            } else if (kecamatanId !== '0') {
                url += `/${kecamatanId}`;
            }

            window.location.href = url;
        });
    }
</script>
</x-navbar>