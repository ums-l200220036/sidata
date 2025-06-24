<table>
    <thead>
        <tr>
            @if(Auth::user()->role === 'opd')
                <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Kecamatan</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Kelurahan</th>
            @elseif(Auth::user()->role === 'kecamatan')
                <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Kelurahan</th>
            @endif
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">{{ $dimensiHeader }}</th>
            <th colspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Laki-laki</th>
            <th colspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Perempuan</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Jumlah</th>
        </tr>
        <tr>
            <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">n</th>
            <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">%</th>
            <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">n</th>
            <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">%</th>
        </tr>
    </thead>
    <tbody>
        @php $currentKecamatan = null; $currentKelurahan = null; @endphp
        @forelse ($structuredData as $kecamatanName => $kecamatanInfo)
            @foreach ($kecamatanInfo['kelurahan'] as $kelurahanName => $kelurahanInfo)
                @foreach ($kelurahanInfo[$dimensionKey] as $dimensiName => $values)
                    <tr>
                        @if(Auth::user()->role === 'opd')
                            @if ($currentKecamatan !== $kecamatanName)
                                <td rowspan="{{ $kecamatanInfo['rowspan'] }}" style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ $kecamatanName }}</td>
                                @php $currentKecamatan = $kecamatanName; @endphp
                            @endif
                        @endif
                        @if(in_array(Auth::user()->role, ['opd', 'kecamatan']))
                            @if ($currentKelurahan !== $kelurahanName)
                                <td rowspan="{{ $kelurahanInfo['rowspan'] }}" style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ $kelurahanName }}</td>
                                @php $currentKelurahan = $kelurahanName; @endphp
                            @endif
                        @endif
                        <td style="text-align: left; vertical-align: middle; border: 1px solid #000;">{{ $dimensiName }}</td>
                        <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($values['laki_n'], 0, ',', '.') }}</td>
                        <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($values['laki_pct'], 2, ',', '.') }}%</td>
                        <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($values['perempuan_n'], 0, ',', '.') }}</td>
                        <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($values['perempuan_pct'], 2, ',', '.') }}%</td>
                        <td style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">{{ number_format($values['jumlah'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                @php $currentKelurahan = null; @endphp
            @endforeach
            @php $currentKecamatan = null; @endphp
        @empty
           @php
                $colspan = 3; // Dimensi, L, P, Jumlah
                if (in_array(Auth::user()->role, ['opd', 'kecamatan'])) $colspan++;
                if (Auth::user()->role === 'opd') $colspan++;
            @endphp
            <tr><td colspan="{{ $colspan }}" style="text-align: center; vertical-align: middle; border: 1px solid #000;">Tidak ada data yang tersedia.</td></tr>
        @endforelse
    </tbody>
</table>