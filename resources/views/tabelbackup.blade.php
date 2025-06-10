
<section class="min-h-screen overflow-y-auto bg-white p-4">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-lg font-bold mb-4 text-center">Jumlah Sekolah, Guru, dan Murid Taman Kanak-Kanak (TK)</h2>
    
        @php
        $data = [
            'Laweyan' => [
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
            'Serengan' => [
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
        ];
        @endphp
    
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm text-center">
                <thead>
                    <tr class="bg-red-200 text-gray-900">
                        <th rowspan="2" class="border border-gray-300 px-2 py-1">Kecamatan</th>
                        <th rowspan="2" class="border border-gray-300 px-2 py-1">Jenis GTK</th>
                        <th rowspan="2" class="border border-gray-300 px-2 py-1">Status</th>
                        <th colspan="4" class="border border-gray-300 px-2 py-1">Tahun</th>
                    </tr>
                    <tr class="bg-red-500 text-white font-semibold">
                        <th class="border border-gray-300 px-2 py-1">2021</th>
                        <th class="border border-gray-300 px-2 py-1">2022</th>
                        <th class="border border-gray-300 px-2 py-1">2023</th>
                        <th class="border border-gray-300 px-2 py-1">2024</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $kecamatan => $gtkItems)
                        @php $rowspanKecamatan = count($gtkItems) * 2; @endphp
                        @foreach($gtkItems as $jenisGtk => $statusItems)
                            @foreach($statusItems as $status => $values)
                            <tr class="{{ $loop->parent->first && $loop->first ? 'bg-red-50' : '' }}">
                                @if($loop->parent->first && $loop->first)
                                <td rowspan="{{ $rowspanKecamatan }}" class="border border-gray-300 px-2 py-1 font-medium">{{ $kecamatan }}</td>
                                @endif
    
                                @if($loop->first)
                                <td rowspan="2" class="border border-gray-300 px-2 py-1">{{ $jenisGtk }}</td>
                                @endif
                                <td class="border border-gray-300 px-2 py-1">{{ $status }}</td>
                                @foreach($values as $value)
                                    <td class="border border-gray-300 px-2 py-1">{{ $value }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="min-h-screen overflow-y-auto bg-white">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-lg font-bold mb-4 text-center">Jumlah Sekolah, Guru, dan Murid Taman Kanak-Kanak (TK)</h2>
    
        @php
        $data = [
            
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
        ];
        @endphp
    
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm text-center">
                <thead>
                    <tr class="bg-red-200 text-gray-900">
                        <th rowspan="2" class="border border-gray-300 px-2 py-1">Jenis GTK</th>
                        <th rowspan="2" class="border border-gray-300 px-2 py-1">Status</th>
                        <th colspan="4" class="border border-gray-300 px-2 py-1">Tahun</th>
                    </tr>
                    <tr class="bg-red-500 text-white font-semibold">
                        <th class="border border-gray-300 px-2 py-1">2021</th>
                        <th class="border border-gray-300 px-2 py-1">2022</th>
                        <th class="border border-gray-300 px-2 py-1">2023</th>
                        <th class="border border-gray-300 px-2 py-1">2024</th>
                    </tr>
                </thead>
                <tbody>
                        @php $rowspanKecamatan = count($gtkItems) * 2; @endphp
                        @foreach($gtkItems as $jenisGtk => $statusItems)
                            @foreach($statusItems as $status => $values)
                            <tr class="{{ $loop->parent->first && $loop->first ? 'bg-red-50' : '' }}">
    
                                @if($loop->first)
                                <td rowspan="2" class="border border-gray-300 px-2 py-1">{{ $jenisGtk }}</td>
                                @endif
                                <td class="border border-gray-300 px-2 py-1">{{ $status }}</td>
                                @foreach($values as $value)
                                    <td class="border border-gray-300 px-2 py-1">{{ $value }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>