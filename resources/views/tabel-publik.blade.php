@extends('layouts.app')

@section('title', 'Tabel Publik')

<x-navbar>
<section class="min-h-screen bg-white">
<div class="px-4 py-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Jelajahi Data Sektoral</h2>

    {{-- FORM FILTER DINAMIS --}}
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <form id="filter-form" method="GET" action="{{ route('laporan.publik', ['indikatorId' => $selectedIndicatorId]) }}" class="flex items-center justify-center gap-6 p-2">
            {{-- Filter Kategori Indikator --}}
            <div class="flex items-center">
                <label for="category-select" class="font-semibold text-gray-700 text-sm w-[150px]">Kategori Data:</label>
                <select name="indikator" id="category-select" class="block w-full min-w-[200px] border border-gray-300 rounded-lg py-2 px-3">
                    @foreach($availableIndicators as $indicator)
                        <option value="{{ $indicator->id }}" {{ $indicator->id == $selectedIndicatorId ? 'selected' : '' }}>
                            {{ $indicator->nama_indikator }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2 p-4 bg-white transition duration-200 ease-in-out">
                    <label for="kecamatan-select" class="font-semibold text-gray-700 text-sm">Kecamatan:</label>
                    <select name="kecamatan" id="kecamatan-select" class="block w-full min-w-[180px] border border-gray-300 rounded-lg py-2 px-3 text-sm text-gray-800 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FE482B] focus:border-transparent transition duration-200 ease-in-out cursor-pointer appearance-none bg-white pr-8">
                        <option value="0">Semua Kecamatan</option>
                        @foreach($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->id }}" {{ $kecamatan->id == $selectedKecamatanId ? 'selected' : '' }}>{{ $kecamatan->kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 p-4 bg-white transition duration-200 ease-in-out">
                    <label for="kelurahan-select" class="font-semibold text-gray-700 text-sm">Kelurahan:</label>
                    <select name="kelurahan" id="kelurahan-select" class="block w-full min-w-[180px] border border-gray-300 rounded-lg py-2 px-3 text-sm text-gray-800 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FE482B] focus:border-transparent transition duration-200 ease-in-out cursor-pointer appearance-none bg-white pr-8">
                        <option value="0">Semua Kelurahan</option>
                        @foreach($kelurahans as $kelurahan)
                            <option value="{{ $kelurahan->id }}" {{ $kelurahan->id == $selectedKelurahanId ? 'selected' : '' }}>{{ $kelurahan->kelurahan }}</option>
                        @endforeach
                    </select>
                </div>
        </form>
        <a href="{{ route('laporan.export.publik', ['indikatorId' => $selectedIndicatorId, 'kecamatan' => $selectedKecamatanId, 'kelurahan' => $selectedKelurahanId]) }}"
           class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
            <i class="fas fa-download mr-2"></i> Unduh Excel
        </a>
    </div>

    {{-- TABEL DATA --}}
    <div class="overflow-x-auto flex justify-center shadow-lg">
        <table class="min-w-full border-collapse border border-gray-200 text-sm">
            <thead class="bg-[#FE482B] text-white">
                <tr>
                    <th rowspan="2" class="border p-3 font-semibold text-center">Kecamatan</th>
                    <th rowspan="2" class="border p-3 font-semibold text-center">Kelurahan</th>
                    {{-- Header Kolom Dinamis --}}
                    <th rowspan="2" class="border p-3 font-semibold text-center">{{ $dimensiHeader }}</th>
                    <th colspan="{{ count($years) }}" class="border p-3 font-semibold text-center">Tahun</th>
                </tr>
                <tr class="bg-[#e03d25] text-white">
                    @foreach($years as $year)
                        <th class="border p-2 text-center">{{ $year }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php $currentKecamatan = null; $currentKelurahan = null; @endphp
                @forelse($structuredData as $kecamatanName => $kecamatanInfo)
                    
                    {{-- Loop ke dalam key 'kelurahan' yang sekarang sudah pasti ada --}}
                    @foreach($kecamatanInfo['kelurahan'] as $kelurahanName => $kelurahanInfo)
                        
                        {{-- Loop ke dalam key 'dimensi' --}}
                        @foreach($kelurahanInfo['dimensi'] as $dimensiName => $values)
                            <tr class="{{ $loop->parent->parent->iteration % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100">
                                
                                {{-- Logika rowspan sekarang akan bekerja dengan benar --}}
                                @if($currentKecamatan !== $kecamatanName)
                                    <td rowspan="{{ $kecamatanInfo['rowspan'] }}" class="border px-2 py-2 font-bold align-middle text-center">{{ $kecamatanName }}</td>
                                    @php $currentKecamatan = $kecamatanName; @endphp
                                @endif

                                @if($currentKelurahan !== $kelurahanName)
                                    <td rowspan="{{ $kelurahanInfo['rowspan'] }}" class="border px-2 py-2 font-medium align-middle text-center">{{ $kelurahanName }}</td>
                                    @php $currentKelurahan = $kelurahanName; @endphp
                                @endif
                                
                                <td class="border px-2 py-2 text-left">{{ $dimensiName }}</td>
                                
                                @foreach($values as $value)
                                    <td class="border px-2 py-2 text-center">{{ is_numeric($value) ? number_format($value, 0, ',', '.') : $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        @php $currentKelurahan = null; @endphp
                    @endforeach
                @empty
                    <tr>
                        <td colspan="{{ 3 + count($years) }}" class="p-4 text-center text-gray-500">
                            Tidak ada data yang tersedia untuk filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</section>

{{-- Script untuk mengubah URL saat Kategori Indikator diganti --}}
<script>
    // Pastikan script ini berjalan setelah seluruh halaman dimuat
    document.addEventListener('DOMContentLoaded', function () {
        
        // Ambil semua elemen filter yang kita butuhkan
        const filterForm = document.getElementById('filter-form');
        const categorySelect = document.getElementById('category-select');
        const kecamatanSelect = document.getElementById('kecamatan-select');
        const kelurahanSelect = document.getElementById('kelurahan-select');

        // --- Logika untuk Filter Kategori (Indikator) ---
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                const selectedIndicatorId = this.value;
                // Saat kategori diganti, kita langsung pindah ke URL baru untuk indikator tersebut.
                // Ini akan me-reset filter kecamatan dan kelurahan secara otomatis.
                // Pastikan route 'laporan.publik' sudah ada di web.php
                window.location.href = `{{ route('laporan.publik', '') }}/${selectedIndicatorId}`;
            });
        }

        // --- Logika untuk Filter Kecamatan ---
        if (kecamatanSelect) {
            kecamatanSelect.addEventListener('change', function() {
                // Saat kecamatan diganti, otomatis reset pilihan kelurahan menjadi "Semua Kelurahan"
                if (kelurahanSelect) {
                    kelurahanSelect.value = '0';
                }
                // Lalu submit form untuk memuat ulang halaman dengan filter baru
                filterForm.submit();
            });
        }

        // --- Logika untuk Filter Kelurahan ---
        if (kelurahanSelect) {
            kelurahanSelect.addEventListener('change', function() {
                // Saat kelurahan diganti, langsung submit form
                filterForm.submit();
            });
        }
    });
</script>
</x-navbar>