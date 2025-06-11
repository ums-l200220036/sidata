@extends('layouts.app')

@section('title', $indikatorTitle)

<x-navbar>
<section class="min-h-screen overflow-y-auto bg-white p-4">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-3xl font-bold text-gray-800">{{ $indikatorTitle }}</h2>
        <div class="flex">
            <form id="filter-form" class="flex flex-wrap items-center justify-center gap-6 py-2">
                <div class="flex items-center gap-2 p-4 bg-white transition duration-200 ease-in-out">
                    <label for="year-select" class="font-semibold text-gray-700 text-sm">Tahun:</label>
                    <select name="year" id="year-select" class="block w-full min-w-[120px] border border-gray-300 rounded-lg py-2 px-3 text-sm text-gray-800 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FE482B] focus:border-transparent transition duration-200 ease-in-out cursor-pointer appearance-none bg-white pr-8">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $year == $tahunAnalisis ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                @if(Auth::check() && Auth::user()->role === 'opd')
                <div class="flex items-center gap-2 p-4 bg-white transition duration-200 ease-in-out">
                    <label for="kecamatan-select" class="font-semibold text-gray-700 text-sm">Kecamatan:</label>
                    <select name="kecamatan" id="kecamatan-select" class="block w-full min-w-[180px] border border-gray-300 rounded-lg py-2 px-3 text-sm text-gray-800 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FE482B] focus:border-transparent transition duration-200 ease-in-out cursor-pointer appearance-none bg-white pr-8">
                        <option value="0">Semua Kecamatan</option>
                        @foreach($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->id }}" {{ $kecamatan->id == $selectedKecamatanId ? 'selected' : '' }}>{{ $kecamatan->kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if(Auth::check() && in_array(Auth::user()->role, ['opd', 'kecamatan']))
                <div class="flex items-center gap-2 p-4 bg-white transition duration-200 ease-in-out">
                    <label for="kelurahan-select" class="font-semibold text-gray-700 text-sm">Kelurahan:</label>
                    <select name="kelurahan" id="kelurahan-select" class="block w-full min-w-[180px] border border-gray-300 rounded-lg py-2 px-3 text-sm text-gray-800 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FE482B] focus:border-transparent transition duration-200 ease-in-out cursor-pointer appearance-none bg-white pr-8">
                        <option value="0">Semua Kelurahan</option>
                        @foreach($kelurahans as $kelurahan)
                            <option value="{{ $kelurahan->id }}" {{ $kelurahan->id == $selectedKelurahanId ? 'selected' : '' }}>{{ $kelurahan->kelurahan }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto flex justify-center shadow-lg">
            <table class="min-w-full border-collapse border border-gray-300 text-sm text-center">
                <thead class="bg-[#FE482B] text-white">
                    <tr>
                        <th rowspan="2" class="border border-gray-200 px-4 py-3 font-semibold">Kecamatan</th>
                        <th rowspan="2" class="border border-gray-200 px-4 py-3 font-semibold">Kelurahan</th>
                        <th rowspan="2" class="border border-gray-200 px-4 py-3 font-semibold">{{ $dimensiHeader }}</th>
                        <th colspan="2" class="border border-gray-200 px-4 py-3 font-semibold">Tahun ke {{$tahunSebelumnya}} Semester 1</th>
                        <th colspan="2" class="border border-gray-200 px-4 py-3 font-semibold">Tahun ke {{$tahunSebelumnya}} Semester 2</th>
                        <th colspan="2" class="border border-gray-200 px-4 py-3 font-semibold">Tahun ke {{$tahunAnalisis}} Semester 1</th>
                    </tr>
                    <tr class="bg-[#e03d25] text-white">
                        <th class="border border-gray-200 px-4 py-2">Individu</th><th class="border border-gray-200 px-4 py-2">Keluarga</th>
                        <th class="border border-gray-200 px-4 py-2">Individu</th><th class="border border-gray-200 px-4 py-2">Keluarga</th>
                        <th class="border border-gray-200 px-4 py-2">Individu</th><th class="border border-gray-200 px-4 py-2">Keluarga</th>
                    </tr>
                </thead>
                <tbody>
                    <tbody>
                        @php $currentKecamatan = null; @endphp
                        @forelse ($structuredData as $kecamatanName => $kecamatanInfo)
                            @php $currentKelurahan = null; @endphp
                            @foreach ($kecamatanInfo['kelurahan'] as $kelurahanName => $kelurahanInfo)
                                
                                {{-- Loop untuk data prioritas (tidak ada perubahan) --}}
                                @foreach ($kelurahanInfo['prioritas'] as $prioritasName => $values)
                                    <tr class="hover:bg-gray-50 {{ $loop->parent->parent->iteration % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                        
                                        @if ($currentKecamatan !== $kecamatanName)
                                            <td rowspan="{{ $kecamatanInfo['rowspan'] }}" class="border border-gray-200 px-4 py-2 font-bold align-middle text-center">{{ $kecamatanName }}</td>
                                            @php $currentKecamatan = $kecamatanName; @endphp
                                        @endif
                                        
                                        @if ($currentKelurahan !== $kelurahanName)
                                            <td rowspan="{{ $kelurahanInfo['rowspan'] }}" class="border border-gray-200 px-4 py-2 font-medium align-middle text-center">{{ $kelurahanName }}</td>
                                            @php $currentKelurahan = $kelurahanName; @endphp
                                        @endif
                                        
                                        <td class="border border-gray-200 px-4 py-2 text-left">{{ $prioritasName }}</td>
                                        
                                        <td class="border border-gray-200 px-4 py-2">{{ number_format($values["{$tahunSebelumnya}_s1"]['individu']) }}</td>
                                        <td class="border border-gray-200 px-4 py-2">{{ number_format($values["{$tahunSebelumnya}_s1"]['keluarga']) }}</td>
                                        <td class="border border-gray-200 px-4 py-2">{{ number_format($values["{$tahunSebelumnya}_s2"]['individu']) }}</td>
                                        <td class="border border-gray-200 px-4 py-2">{{ number_format($values["{$tahunSebelumnya}_s2"]['keluarga']) }}</td>
                                        <td class="border border-gray-200 px-4 py-2">{{ number_format($values["{$tahunAnalisis}_s1"]['individu']) }}</td>
                                        <td class="border border-gray-200 px-4 py-2">{{ number_format($values["{$tahunAnalisis}_s1"]['keluarga']) }}</td>
                                    </tr>
                                @endforeach
                                
                                {{-- ================================================================ --}}
                                {{-- PERBAIKAN UTAMA DI SINI: STRUKTUR BARIS TOTAL --}}
                                {{-- ================================================================ --}}
                                <tr class="bg-gray-200 hover:bg-gray-300 font-bold">
                                    {{-- Kita TIDAK menggunakan colspan. Kita hanya perlu satu sel untuk label "Total" --}}
                                    {{-- karena sel Kecamatan & Kelurahan dari atas sudah mencakup baris ini berkat rowspan. --}}
                                    <td class="border-t-2 border-b-2 border-gray-300 px-4 py-2 text-left">Total</td>
                                    
                                    @php $totalValues = $kelurahanInfo['total']; @endphp
                                    <td class="border-t-2 border-b-2 border-gray-300 px-4 py-2">{{ number_format($totalValues["{$tahunSebelumnya}_s1"]['individu']) }}</td>
                                    <td class="border-t-2 border-b-2 border-gray-300 px-4 py-2">{{ number_format($totalValues["{$tahunSebelumnya}_s1"]['keluarga']) }}</td>
                                    <td class="border-t-2 border-b-2 border-gray-300 px-4 py-2">{{ number_format($totalValues["{$tahunSebelumnya}_s2"]['individu']) }}</td>
                                    <td class="border-t-2 border-b-2 border-gray-300 px-4 py-2">{{ number_format($totalValues["{$tahunSebelumnya}_s2"]['keluarga']) }}</td>
                                    <td class="border-t-2 border-b-2 border-gray-300 px-4 py-2">{{ number_format($totalValues["{$tahunAnalisis}_s1"]['individu']) }}</td>
                                    <td class="border-t-2 border-b-2 border-gray-300 px-4 py-2">{{ number_format($totalValues["{$tahunAnalisis}_s1"]['keluarga']) }}</td>
                                </tr>
                                
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" class="border border-gray-200 px-4 py-4 text-center text-gray-500">Data tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    document.getElementById('filter-form').addEventListener('change', function(e) {
        const tahun = document.getElementById('year-select').value;
        const kecamatanSelect = document.getElementById('kecamatan-select');
        const kelurahanSelect = document.getElementById('kelurahan-select');
        const kecamatanId = kecamatanSelect ? kecamatanSelect.value : '0';
        const kelurahanId = kelurahanSelect ? kelurahanSelect.value : '0';
        
        // Gunakan nama route yang benar untuk laporan ini
        const baseUrl = "{{ route('laporan.prioritas', ['indikatorId' => $indikator->id]) }}";
        
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