@php
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
    $times = range(7, 16); // 07:00 to 17:00
    
    // Extract all items from jadwal
    $allItems = [];
    foreach($jadwal->jadwal as $ruangData) {
        foreach($ruangData['items'] as $item) {
            $allItems[] = $item;
        }
    }
    
    // Get unique rooms and sort them
    $rooms = collect($allItems)->pluck('namaRuangan')->unique()->sort()->values()->toArray();
    
    // Map items by day, time, room
    $matrix = [];
    foreach($allItems as $item) {
        $matrix[$item['hari']][$item['jamMulai']][$item['namaRuangan']] = $item;
    }
    
    function getSemesterBgColor($semester) {
        switch($semester) {
            case 1: return '#FCE883';
            case 2: return '#FCE883';
            case 3: return '#90EE90';
            case 4: return '#90EE90';
            case 5: return '#ADD8E6';
            case 6: return '#ADD8E6';
            default: return '#AAAAAA';
        }
    }
@endphp

<table>
    <thead>
        <tr>
            <th colspan="{{ count($rooms) + 2 }}" style="text-align: center; font-weight: bold; font-size: 16px;">
                {{ $jadwal->title }}
            </th>
        </tr>
        <tr>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold; background-color: #f3f4f6; width: 100px;">Hari</th>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold; background-color: #f3f4f6; width: 120px;">Jam</th>
            @foreach($rooms as $room)
                <th style="border: 1px solid #000000; text-align: center; font-weight: bold; background-color: #f3f4f6; width: 300px;">{{ $room }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($days as $day)
            @php
                // Track covered cells for rowspan
                $covered = [];
            @endphp
            @foreach($times as $idx => $t)
                <tr style="height: 60px;">
                    @if($idx === 0)
                        <td rowspan="{{ count($times) }}" style="border: 1px solid #000000; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">
                            {{ $day }}
                        </td>
                    @endif
                    
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle; background-color: #ffffff;">
                        {{ str_pad($t, 2, '0', STR_PAD_LEFT) }}.00 - {{ str_pad($t+1, 2, '0', STR_PAD_LEFT) }}.00
                    </td>
                    
                    @foreach($rooms as $room)
                        @if(isset($covered[$room]) && $covered[$room] > 0)
                            @php $covered[$room]--; @endphp
                        @else
                            @if(isset($matrix[$day][$t][$room]))
                                @php
                                    $item = $matrix[$day][$t][$room];
                                    $durasi = $item['jamSelesai'] - $item['jamMulai'];
                                    $covered[$room] = $durasi - 1;
                                    $bgColor = getSemesterBgColor($item['semester']);
                                @endphp
                                <td rowspan="{{ $durasi }}" style="border: 1px solid #000000; background-color: {{ $bgColor }}; vertical-align: middle; text-align: center; word-wrap: break-word;">
                                    <strong>{{ $item['namaJadwal'] }}</strong><br>
                                    {{ $item['namaDosen'] }}<br>
                                    @if(!empty($item['namaTeknisi']))
                                        {{ $item['namaTeknisi'] }} (Teknisi)
                                    @endif
                                </td>
                            @else
                                <td style="border: 1px solid #000000; background-color: #ffffff;"></td>
                            @endif
                        @endif
                    @endforeach
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

<table>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td style="font-weight: bold;" colspan="2">Legend / Keterangan:</td>
    </tr>
    <tr>
        <td style="background-color: #FCE883; border: 1px solid #000000; text-align: center;"></td>
        <td> Semester {{ strtolower($jadwal->semester) === 'ganjil' ? '1' : '2' }} (Kuning)</td>
    </tr>
    <tr>
        <td style="background-color: #90EE90; border: 1px solid #000000; text-align: center;"></td>
        <td> Semester {{ strtolower($jadwal->semester) === 'ganjil' ? '3' : '4' }} (Hijau)</td>
    </tr>
    <tr>
        <td style="background-color: #ADD8E6; border: 1px solid #000000; text-align: center;"></td>
        <td> Semester {{ strtolower($jadwal->semester) === 'ganjil' ? '5' : '6' }} (Biru)</td>
    </tr>
</table>
