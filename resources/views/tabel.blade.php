@extends('layouts.app')

@section('title', $indikatorTitle) {{-- Menggunakan judul dari controller --}}

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
                        <th rowspan="2" class="border border-gray-200 px-3 py-2">Kecamatan</th>
                        <th rowspan="2" class="border border-gray-200 px-3 py-2">Kelurahan</th>
                        <th rowspan="2" class="border border-gray-200 px-3 py-2">{{ $dimensiHeader }}</th> {{-- <-- PERBAIKAN DI SINI --}}
                        <th colspan="{{ count($targetYears) }}" class="border border-gray-200 px-3 py-2">Tahun</th>
                    </tr>
                    <tr class="bg-blue-500 text-white font-semibold">
                        @foreach($targetYears as $year)
                            <th class="border border-gray-200 px-3 py-2">{{ $year }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php $kecamatanRendered = false; @endphp
                    @foreach($structuredData as $kecamatanName => $kecamatanInfo)
                        @php $kelurahanRendered = false; @endphp
                        @foreach($kecamatanInfo['kelurahan'] as $kelurahanName => $kelurahanInfo)
                            @foreach($kelurahanInfo['dimensi'] as $dimensiName => $dimensiValues)
                                <tr class="{{ $loop->parent->parent->iteration % 2 === 0 ? 'bg-white' : 'bg-blue-50' }}">
                                    {{-- Kolom Kecamatan --}}
                                    @if(!$kecamatanRendered)
                                        <td rowspan="{{ $kecamatanInfo['rowspan'] }}" class="border border-gray-200 px-3 py-2 font-medium text-gray-800">
                                            {{ $kecamatanName }}
                                        </td>
                                        @php $kecamatanRendered = true; @endphp
                                    @endif

                                    {{-- Kolom Kelurahan --}}
                                    @if(!$kelurahanRendered)
                                        <td rowspan="{{ $kelurahanInfo['rowspan'] }}" class="border border-gray-200 px-3 py-2 text-gray-800">
                                            {{ $kelurahanName }}
                                        </td>
                                        @php $kelurahanRendered = true; @endphp
                                    @endif

                                    {{-- Kolom Agama (Dimensi) --}}
                                    <td class="border border-gray-200 px-3 py-2 text-gray-700">{{ $dimensiName }}</td>

                                    {{-- Kolom Nilai Tahun --}}
                                    @foreach($targetYears as $year)
                                        <td class="border border-gray-200 px-3 py-2">
                                            {{ $dimensiValues[$year] ?? 'N/A' }} {{-- Tampilkan nilai atau 'N/A' jika kosong --}}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            @php $kelurahanRendered = false; @endphp {{-- Reset untuk kelurahan berikutnya --}}
                        @endforeach
                        @php $kecamatanRendered = false; @endphp {{-- Reset untuk kecamatan berikutnya --}}
                    @endforeach

                    @if(empty($structuredData))
                        <tr>
                            <td colspan="{{ 3 + count($targetYears) }}" class="border border-gray-200 px-3 py-2 text-center text-gray-700">
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