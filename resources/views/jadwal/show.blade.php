<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">{{ $jadwal->title }}</h1>
            <p class="text-slate-500 mt-1">Semester: {{ strtoupper($jadwal->semester) }} | Status: 
                @if($jadwal->is_success)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Menunggu/Gagal</span>
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <!-- EXCEL EXPORT BUTTON -->
            <a href="{{ route('jadwal.export', $jadwal->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            
            <a href="{{ route('jadwal.list') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if($jadwal->unscheduled_count > 0)
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 text-red-500"><i class="fas fa-exclamation-circle text-xl"></i></div>
                <div>
                    <h3 class="text-sm font-bold text-red-800">Terdapat {{ $jadwal->unscheduled_count }} kelas yang gagal dijadwalkan</h3>
                    <ul class="mt-2 space-y-1 text-sm text-red-700 list-disc list-inside">
                        @foreach($jadwal->unscheduled_items as $uns)
                            <li>{{ $uns['namaMk'] ?? $uns['nama_mk'] }} - {{ $uns['namaDosen'] ?? $uns['nama_dosen'] }} (Alasan: {{ $uns['alasan'] }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Tampilan Berdasarkan Hari (Dikelompokkan per Ruangan) -->
        <div>
            @forelse($jadwal->jadwal_view as $hariData)
                <div class="border-b border-slate-200 last:border-0">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h2 class="font-extrabold text-slate-800 text-xl tracking-tight uppercase"><i class="fas fa-calendar-day mr-2 text-blue-600"></i>{{ $hariData['nama'] }}</h2>
                    </div>
                    <div class="p-6">
                        @php
                            $itemsByRuangan = collect($hariData['items'])->groupBy('namaRuangan')->sortKeys();
                        @endphp
                        
                        @if($itemsByRuangan->isEmpty())
                            <p class="text-slate-400 text-sm italic">Tidak ada jadwal</p>
                        @else
                            <div class="space-y-8">
                                @foreach($itemsByRuangan as $ruangan => $items)
                                    <div>
                                        <h3 class="font-bold text-slate-700 mb-3 border-b border-slate-100 pb-2 text-lg">
                                            <i class="fas fa-door-open text-slate-400 mr-2"></i>{{ $ruangan }}
                                        </h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                            @foreach($items as $item)
                                                <div class="bg-white border border-slate-200 rounded-xl p-4 hover:border-blue-300 hover:shadow-md transition-all relative overflow-hidden group">
                                                    <div class="absolute top-0 left-0 w-1 h-full bg-blue-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                                    <div class="flex justify-between items-start mb-2">
                                                        <span class="inline-flex items-center px-2 py-1 rounded bg-blue-50 text-blue-700 text-xs font-bold font-mono">
                                                            {{ str_pad($item['jamMulai'], 2, '0', STR_PAD_LEFT) }}:00 - {{ str_pad($item['jamSelesai'], 2, '0', STR_PAD_LEFT) }}:00
                                                        </span>
                                                    </div>
                                                    <h3 class="font-bold text-slate-800 text-sm leading-snug mb-1">{{ $item['namaJadwal'] }}</h3>
                                                    <div class="space-y-1 mt-3">
                                                        <p class="text-xs text-slate-500 flex items-center gap-1.5">
                                                            <i class="fas fa-user-tie w-4 text-center"></i> {{ $item['namaDosen'] }}
                                                        </p>
                                                        @if(!empty($item['namaTeknisi']))
                                                        <p class="text-xs text-slate-500 flex items-center gap-1.5">
                                                            <i class="fas fa-tools w-4 text-center"></i> {{ $item['namaTeknisi'] }}
                                                        </p>
                                                        @endif
                                                        <p class="text-xs text-slate-500 flex items-center gap-1.5">
                                                            <i class="fas fa-layer-group w-4 text-center"></i> {{ $item['sks'] }} SKS (Semester {{ $item['semester'] }})
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500">Jadwal kosong.</div>
            @endforelse
        </div>
    </div>
</div>
</x-app-layout>
