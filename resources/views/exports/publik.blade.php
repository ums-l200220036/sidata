<table>
    <thead>
        <tr>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Kecamatan</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Kelurahan</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">{{ $dimensiHeader }}</th>
            <th colspan="{{ count($years) }}" style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">Tahun</th>
        </tr>
        <tr>
            @foreach($years as $year)
                <th style="text-align: center; vertical-align: middle; font-weight: bold; border: 1px solid #000;">{{ $year }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php $currentKecamatan = null; $currentKelurahan = null; @endphp
        @forelse($structuredData as $kecamatanName => $kecamatanInfo)
            @foreach($kecamatanInfo['kelurahan'] as $kelurahanName => $kelurahanInfo)
                @foreach($kelurahanInfo['dimensi'] as $dimensiName => $values)
                    <tr>
                        @if($currentKecamatan !== $kecamatanName)
                            <td rowspan="{{ $kecamatanInfo['rowspan'] }}" style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ $kecamatanName }}</td>
                            @php $currentKecamatan = $kecamatanName; @endphp
                        @endif
                        @if($currentKelurahan !== $kelurahanName)
                            <td rowspan="{{ $kelurahanInfo['rowspan'] }}" style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ $kelurahanName }}</td>
                            @php $currentKelurahan = $kelurahanName; @endphp
                        @endif
                        <td style="text-align: left; vertical-align: middle; border: 1px solid #000;">{{ $dimensiName }}</td>
                        @foreach($values as $value)
                            <td style="text-align: center; vertical-align: middle; border: 1px solid #000;">{{ is_numeric($value) ? number_format($value, 0, ',', '.') : $value }}</td>
                        @endforeach
                    </tr>
                @endforeach
                @php $currentKelurahan = null; @endphp
            @endforeach
        @empty
            <tr>
                <td colspan="{{ 3 + count($years) }}" style="text-align: center; vertical-align: middle; border: 1px solid #000;">
                    Tidak ada data yang tersedia.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>