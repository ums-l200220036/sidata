<table>
    <thead>
        <tr>
            @if (Auth::user()->role === 'opd')
                <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Kecamatan</th>
            @endif
            @if (in_array(Auth::user()->role, ['opd', 'kecamatan']))
                <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Kelurahan</th>
            @endif
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Usia</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Jenis Kelamin</th>
            @foreach ($uniqueYearsInView as $year)
                <th colspan="3" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">{{ $year }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach ($uniqueYearsInView as $year)
                <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">ASN</th>
                <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Non ASN</th>
                <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Total</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse ($structuredData as $kecName => $kecInfo)
            @php $isFirstRowOfKecamatan = true; @endphp
            @foreach ($kecInfo['kelurahans'] as $kelName => $kelInfo)
                @php $isFirstRowOfKelurahan = true; @endphp
                @foreach ($kelInfo['usias'] as $usiaName => $usiaInfo)
                    @php $isFirstRowOfUsia = true; @endphp
                    @foreach ($usiaInfo['rows'] as $row)
                        <tr>
                            @if (Auth::user()->role === 'opd' && $isFirstRowOfKecamatan)
                                <td rowspan="{{ $kecInfo['rowspan_kecamatan'] }}" style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ $kecName }}</td>
                                @php $isFirstRowOfKecamatan = false; @endphp
                            @endif

                            @if (in_array(Auth::user()->role, ['opd', 'kecamatan']) && $isFirstRowOfKelurahan)
                                <td rowspan="{{ $kelInfo['rowspan_kelurahan'] }}" style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ $kelName }}</td>
                                @php $isFirstRowOfKelurahan = false; @endphp
                            @endif

                            @if ($isFirstRowOfUsia)
                                <td rowspan="{{ $usiaInfo['rowspan_usia'] }}" style="text-align: left; vertical-align: middle; border: 1px solid #000;">{{ $usiaName }}</td>
                                @php $isFirstRowOfUsia = false; @endphp
                            @endif

                            <td style="text-align: left; vertical-align: middle; border: 1px solid #000;">{{ $row['jenis_kelamin'] }}</td>
                            @foreach ($uniqueYearsInView as $year)
                                <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($row['yearly_data'][$year]['ASN'], 0, ',', '.') }}</td>
                                <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($row['yearly_data'][$year]['Non ASN'], 0, ',', '.') }}</td>
                                <td style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">{{ number_format($row['yearly_data'][$year]['Total'], 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @empty
            @php
                $colspan = 2 + (count($uniqueYearsInView) * 3);
                if (in_array(Auth::user()->role, ['opd', 'kecamatan'])) $colspan++;
                if (Auth::user()->role === 'opd') $colspan++;
            @endphp
            <tr>
                <td colspan="{{ $colspan }}" style="text-align: center; vertical-align: middle; border: 1px solid #000;">Data tidak ditemukan untuk filter ini.</td>
            </tr>
        @endforelse

        @if(count($structuredData) > 0)
            @php
                $colspanForTotal = 2;
                if (in_array(Auth::user()->role, ['opd', 'kecamatan'])) $colspanForTotal++;
                if (Auth::user()->role === 'opd') $colspanForTotal++;
            @endphp
            <tr style="font-weight: bold;">
                <td colspan="{{ $colspanForTotal }}" style="text-align: right; vertical-align: middle; border: 1px solid #000;">Jumlah</td>
                @foreach ($uniqueYearsInView as $year)
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($grandTotalsPerYear[$year]['ASN'], 0, ',', '.') }}</td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($grandTotalsPerYear[$year]['Non ASN'], 0, ',', '.') }}</td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($grandTotalsPerYear[$year]['Total'], 0, ',', '.') }}</td>
                @endforeach
            </tr>
        @endif
    </tbody>
</table>