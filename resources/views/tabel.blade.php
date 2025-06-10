@extends('layouts.app')

@section('title', 'Data Table')

<x-navbar>
<section class="min-h-screen overflow-y-auto bg-white p-4">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-lg font-bold mb-4 text-center">
            Jumlah Sekolah, Guru, dan Murid Taman Kanak-Kanak (TK)
        </h2>

        @php
        $data = [
            'Laweyan' => [
                'kelurahan' => ['Gilingan', 'Kerten', 'Pajang'],
                'data' => [
                    'Islam' => [1986, 2317, 2314, 2347],
                    'Hindu'=> [40, 31, 29, 39],
                    'Budha'=> [20, 18, 15, 12],
                    'Katholik'=> [22, 21, 20, 19],
                    'Kristen'=> [50, 53, 49, 45],
                    'Lain-lain'=> [3, 2, 1, 0],
                ],
            ],
            'Serengan' => [
                'kelurahan' => ['Jayengan', 'Danukusuman', 'Serengan'],
                'data' => [
                    'Islam' => [1500, 1600, 1700, 1800],
                    'Hindu'=> [30, 28, 25, 20],
                    'Budha'=> [10, 8, 6, 5],
                    'Katholik'=> [18, 19, 20, 22],
                    'Kristen'=> [40, 42, 41, 39],
                    'Lain-lain'=> [1, 2, 0, 0],
                ],
            ],
        ];
        @endphp

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm text-center rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-blue-200 text-blue-900 font-semibold">
                        <th rowspan="2" class="border border-gray-200 px-3 py-2">Kecamatan</th>
                        <th rowspan="2" class="border border-gray-200 px-3 py-2">Kelurahan</th>
                        <th rowspan="2" class="border border-gray-200 px-3 py-2">Agama</th>
                        <th colspan="4" class="border border-gray-200 px-3 py-2">Tahun</th>
                    </tr>
                    <tr class="bg-blue-500 text-white font-semibold">
                        <th class="border border-gray-200 px-3 py-2">2021</th>
                        <th class="border border-gray-200 px-3 py-2">2022</th>
                        <th class="border border-gray-200 px-3 py-2">2023</th>
                        <th class="border border-gray-200 px-3 py-2">2024</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rowIndex = 0; @endphp
                    @foreach($data as $kecamatan => $info)
                        @php
                            $kelurahanList = $info['kelurahan'];
                            $agamaList = $info['data'];
                            $rowspanKecamatan = count($kelurahanList) * count($agamaList);
                        @endphp

                        @foreach($kelurahanList as $kIdx => $kelurahan)
                            @foreach($agamaList as $agama => $values)
                                <tr class="{{ $rowIndex % 2 === 0 ? 'bg-white' : 'bg-blue-50' }}">
                                    @if($loop->parent->first && $loop->first)
                                        <td rowspan="{{ $rowspanKecamatan }}" class="border border-gray-200 px-3 py-2 font-medium text-gray-800">
                                            {{ $kecamatan }}
                                        </td>
                                    @endif

                                    @if($loop->first)
                                        <td rowspan="{{ count($agamaList) }}" class="border border-gray-200 px-3 py-2 text-gray-800">
                                            {{ $kelurahan }}
                                        </td>
                                    @endif

                                    <td class="border border-gray-200 px-3 py-2 text-gray-700">{{ $agama }}</td>
                                    @foreach($values as $value)
                                        <td class="border border-gray-200 px-3 py-2">{{ $value }}</td>
                                    @endforeach
                                </tr>
                                @php $rowIndex++; @endphp
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>



</section>
</x-navbar>
