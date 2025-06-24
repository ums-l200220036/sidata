<table>
    <thead>
        <tr>
            {{-- Header Kolom Kecamatan (Hanya untuk OPD) --}}
            @if (Auth::user()->role === 'opd')
                <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Kecamatan</th>
            @endif
            
            {{-- Header Kolom Kelurahan (Untuk OPD dan Kecamatan) --}}
            @if (in_array(Auth::user()->role, ['opd', 'kecamatan']))
                <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Kelurahan</th>
            @endif

            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">{{ $dimensiHeader }}</th>
            <th colspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Tahun ke {{ $tahunSebelumnya }} Semester 1</th>
            <th colspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Tahun ke {{ $tahunSebelumnya }} Semester 2</th>
            <th colspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Tahun ke {{ $tahunAnalisis }} Semester 1</th>
        </tr>
        <tr>
            <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Individu</th>
            <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Keluarga</th>
            <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Individu</th>
            <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Keluarga</th>
            <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Individu</th>
            <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Keluarga</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($structuredData as $kecamatanName => $kecamatanInfo)
            @php $isFirstRowOfKecamatan = true; @endphp
            @foreach ($kecamatanInfo['kelurahan'] as $kelurahanName => $kelurahanInfo)
                @php $isFirstRowOfKelurahan = true; @endphp
                @foreach ($kelurahanInfo['prioritas'] as $prioritasName => $values)
                    <tr>
                        {{-- Render <td> Kecamatan hanya di baris paling pertama dari grupnya --}}
                        @if (Auth::user()->role === 'opd' && $isFirstRowOfKecamatan)
                            <td rowspan="{{ $kecamatanInfo['rowspan'] }}" style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ $kecamatanName }}</td>
                            @php $isFirstRowOfKecamatan = false; @endphp
                        @endif

                        {{-- Render <td> Kelurahan hanya di baris paling pertama dari grupnya --}}
                        @if (in_array(Auth::user()->role, ['opd', 'kecamatan']) && $isFirstRowOfKelurahan)
                            <td rowspan="{{ $kelurahanInfo['rowspan'] }}" style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ $kelurahanName }}</td>
                            @php $isFirstRowOfKelurahan = false; @endphp
                        @endif
                        
                        {{-- Kolom yang selalu dirender --}}
                        <td style="text-align: left; vertical-align: middle; border: 1px solid #000;">{{ $prioritasName }}</td>
                        <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($values["{$tahunSebelumnya}_s1"]['individu'], 0, ',', '.') }}</td>
                        <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($values["{$tahunSebelumnya}_s1"]['keluarga'], 0, ',', '.') }}</td>
                        <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($values["{$tahunSebelumnya}_s2"]['individu'], 0, ',', '.') }}</td>
                        <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($values["{$tahunSebelumnya}_s2"]['keluarga'], 0, ',', '.') }}</td>
                        <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($values["{$tahunAnalisis}_s1"]['individu'], 0, ',', '.') }}</td>
                        <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($values["{$tahunAnalisis}_s1"]['keluarga'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                {{-- Baris Total --}}
                <tr style="font-weight: bold;">
                    {{-- Sel "Total" akan otomatis berada di bawah kolom "Prioritas" --}}
                    <td style="text-align: left; vertical-align: middle; border: 1px solid #000;">Total</td>
                    @php $totalValues = $kelurahanInfo['total']; @endphp
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($totalValues["{$tahunSebelumnya}_s1"]['individu'], 0, ',', '.') }}</td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($totalValues["{$tahunSebelumnya}_s1"]['keluarga'], 0, ',', '.') }}</td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($totalValues["{$tahunSebelumnya}_s2"]['individu'], 0, ',', '.') }}</td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($totalValues["{$tahunSebelumnya}_s2"]['keluarga'], 0, ',', '.') }}</td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($totalValues["{$tahunAnalisis}_s1"]['individu'], 0, ',', '.') }}</td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ number_format($totalValues["{$tahunAnalisis}_s1"]['keluarga'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        @empty
            @php
                $colspan = 7;
                if(in_array(Auth::user()->role, ['opd', 'kecamatan'])) $colspan++;
                if(Auth::user()->role === 'opd') $colspan++;
            @endphp
            <tr><td colspan="{{ $colspan }}" style="text-align: center; vertical-align: middle; border: 1px solid #000;">Data tidak ditemukan.</td></tr>
        @endforelse
    </tbody>
</table>