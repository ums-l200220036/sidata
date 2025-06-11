@extends('layouts.app')

@section('title', 'Tabel Publik')

<x-navbar> {{-- x-navbar should be within section content if it is intended to be placed here --}}
    <section class="min-h-screen bg-white">
        <div class="px-4 py-6">
            @php
            $data = [
                'Laweyan' => [
                    'Kerten' => [ // Example Kelurahan in Laweyan
                        'Sekolah' => [
                            'Negeri' => [1, 1, 1, 1],
                            'Swasta' => [54, 54, 54, 54],
                        ],
                        'Guru' => [
                            'Negeri' => [3, 3, 4, 6],
                            'Swasta' => [217, 207, 287, 254],
                        ],
                        'Murid' => [
                            'Negeri' => [40, 31, 29, 39],
                            'Swasta' => [1986, 2317, 2314, 2347],
                        ],
                    ],
                    'Purwosari' => [ // Another example Kelurahan in Laweyan
                        'Sekolah' => [
                            'Negeri' => [2, 2, 2, 2],
                            'Swasta' => [30, 31, 29, 30],
                        ],
                        'Guru' => [
                            'Negeri' => [5, 6, 7, 8],
                            'Swasta' => [100, 110, 120, 130],
                        ],
                        'Murid' => [
                            'Negeri' => [60, 65, 70, 75],
                            'Swasta' => [1500, 1600, 1700, 1800],
                        ],
                    ],
                ],
                'Serengan' => [
                    'Serengan' => [ // Example Kelurahan in Serengan
                        'Sekolah' => [
                            'Negeri' => [1, 1, 1, 1],
                            'Swasta' => [26, 26, 23, 22],
                        ],
                        'Guru' => [
                            'Negeri' => [3, 3, 4, 5],
                            'Swasta' => [65, 65, 98, 84],
                        ],
                        'Murid' => [
                            'Negeri' => [52, 53, 56, 56],
                            'Swasta' => [795, 1017, 1026, 959],
                        ],
                    ],
                ],
            ];

            // Calculate the years for the headers based on the current year
            $currentYear = date('Y');
            $years = [];
            for ($i = 3; $i >= 0; $i--) {
                $years[] = $currentYear - $i;
            }

            // Placeholder for filter variables (no actual logic for them here)
            $availableCategories = ['Sekolah', 'Guru', 'Murid']; // Example categories
            $selectedCategory = 'Sekolah'; // Default selected category
            $kecamatans = collect([
                (object)['id' => 1, 'kecamatan' => 'Laweyan'],
                (object)['id' => 2, 'kecamatan' => 'Serengan'],
                // Add more kecamatan objects as needed for display
            ]);
            $selectedKecamatanId = 0; // Default: All Kecamatans
            $kelurahans = collect([
                (object)['id' => 1, 'kelurahan' => 'Kerten'],
                (object)['id' => 2, 'kelurahan' => 'Purwosari'],
                (object)['id' => 3, 'kelurahan' => 'Serengan'],
                // Add more kelurahan objects as needed for display
            ]);
            $selectedKelurahanId = 0; // Default: All Kelurahans

            // Placeholder for Auth check (assuming a basic Auth facade and user role)
            $userRole = 'opd'; // 'opd', 'kecamatan', or any other role for demonstration
            @endphp

            {{-- Filter Section --}}
            <div class="flex">
                <form id="filter-form" class="flex flex-wrap items-center justify-center gap-6 ">
                    <div class="flex items-center gap-2 p-4 bg-white transition duration-200 ease-in-out">
                        <label for="category-select" class="font-semibold text-gray-700 text-sm">Kategori:</label>
                        <select name="category" id="category-select" class="block w-full min-w-[120px] border border-gray-300 rounded-lg py-2 px-3 text-sm text-gray-800 leading-tight focus:outline-none focus:ring-2 focus:ring-[#FE482B] focus:border-transparent transition duration-200 ease-in-out cursor-pointer appearance-none bg-white pr-8">
                            @foreach($availableCategories as $category)
                                <option value="{{ $category }}" {{ $category == $selectedCategory ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($userRole === 'opd') {{-- Simplified Auth::check() for display --}}
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
                    @if(in_array($userRole, ['opd', 'kecamatan'])) {{-- Simplified Auth::check() for display --}}
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

            <div class="overflow-x-auto flex justify-center shadow-lg rounded-lg mb-10">
                <table class="min-w-full border-collapse border border-gray-200 text-sm">
                    <thead class="bg-[#FE482B] text-white">
                        <tr>
                            <th rowspan="2" class="border border-gray-200 px-4 py-3 font-semibold text-center">Kecamatan</th>
                            <th rowspan="2" class="border border-gray-200 px-4 py-3 font-semibold text-center">Kelurahan</th>
                            <th rowspan="2" class="border border-gray-200 px-4 py-3 font-semibold text-center">Jenis GTK</th>
                            <th rowspan="2" class="border border-gray-200 px-4 py-3 font-semibold text-center">Status</th>
                            <th colspan="4" class="border border-gray-200 px-4 py-3 font-semibold text-center">Tahun</th>
                        </tr>
                        <tr class="bg-[#e03d25] text-white">
                            @foreach($years as $year)
                                <th class="border border-gray-200 px-4 py-2 text-center">{{ $year }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php $kecamatanLoopIndex = 0; @endphp
                        @foreach($data as $kecamatanName => $kelurahanItems)
                            @php
                                $rowspanKecamatan = 0;
                                foreach($kelurahanItems as $kelurahanData) {
                                    $rowspanKecamatan += count($kelurahanData['Sekolah']) + count($kelurahanData['Guru']) + count($kelurahanData['Murid']);
                                }
                            @endphp
                            @php $kelurahanLoopIndex = 0; @endphp
                            @foreach($kelurahanItems as $kelurahanName => $gtkItems)
                                @php
                                    $rowspanKelurahan = count($gtkItems['Sekolah']) + count($gtkItems['Guru']) + count($gtkItems['Murid']);
                                @endphp
                                @php $jenisGtkLoopIndex = 0; @endphp
                                @foreach($gtkItems as $jenisGtkName => $statusItems)
                                    @php $statusLoopIndex = 0; @endphp
                                    @foreach($statusItems as $statusName => $values)
                                        <tr class="{{ $kecamatanLoopIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition duration-150 ease-in-out">
                                            {{-- Kolom Kecamatan --}}
                                            @if($kelurahanLoopIndex === 0 && $jenisGtkLoopIndex === 0 && $statusLoopIndex === 0)
                                                <td rowspan="{{ $rowspanKecamatan }}" class="border border-gray-200 px-4 py-2 font-bold align-middle text-center">{{ $kecamatanName }}</td>
                                            @endif

                                            {{-- Kolom Kelurahan --}}
                                            @if($jenisGtkLoopIndex === 0 && $statusLoopIndex === 0)
                                                <td rowspan="{{ $rowspanKelurahan }}" class="border border-gray-200 px-4 py-2 font-medium align-middle text-left">{{ $kelurahanName }}</td>
                                            @endif

                                            {{-- Kolom Jenis GTK --}}
                                            @if($statusLoopIndex === 0)
                                                <td rowspan="{{ count($statusItems) }}" class="border border-gray-200 px-4 py-2 font-medium align-middle text-left">{{ $jenisGtkName }}</td>
                                            @endif

                                            {{-- Kolom Status --}}
                                            <td class="border border-gray-200 px-4 py-2 text-left">{{ $statusName }}</td>
                                            
                                            {{-- Kolom Tahun --}}
                                            @foreach($values as $value)
                                                <td class="border border-gray-200 px-4 py-2 text-center">{{ $value }}</td>
                                            @endforeach
                                        </tr>
                                        @php $statusLoopIndex++; @endphp
                                    @endforeach
                                    @php $jenisGtkLoopIndex++; @endphp
                                @endforeach
                                @php $kelurahanLoopIndex++; @endphp
                            @endforeach
                            @php $kecamatanLoopIndex++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-navbar>