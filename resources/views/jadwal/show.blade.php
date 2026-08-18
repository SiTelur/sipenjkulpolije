<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8" x-data="{ showModalError: false, showRawJson: false, selectedIndex: null }">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $jadwal->title }}</h1>
            <p class="text-slate-500 mt-1">Semester: {{ strtoupper($jadwal->semester) }} | Status: 
                @if($jadwal->is_success)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Menunggu/Gagal</span>
                @endif
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
            @if($jadwal->unscheduled_count > 0)
                <button @click="showModalError = true" type="button" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm cursor-pointer">
                    <i class="fas fa-database"></i> Audit Error (DB)
                </button>
            @endif

            <!-- EXCEL EXPORT BUTTON -->
            <a href="{{ route('jadwal.export', $jadwal->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            
            <a href="{{ route('jadwal.list') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- ─── Error State: MK Tidak Terjadwal ──────────────────────────────── --}}
    @if($jadwal->unscheduled_count > 0)
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 overflow-hidden shadow-sm">
        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-red-200 bg-red-100/60 flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-red-500 flex items-center justify-center shadow-sm flex-shrink-0">
                    <i class="fas fa-calendar-times text-white text-base"></i>
                </div>
                <div>
                    <h3 class="font-bold text-red-900 text-sm leading-tight">Penjadwalan Tidak Lengkap</h3>
                    <p class="text-red-600 text-xs mt-0.5">Terdapat data bermasalah/overconstrained di database</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button @click="showModalError = true" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-all shadow-sm cursor-pointer">
                    <i class="fas fa-search-plus"></i> Detail Error DB
                </button>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-500 text-white text-xs font-bold shadow-sm">
                    <i class="fas fa-exclamation-triangle text-xs"></i>
                    {{ $jadwal->unscheduled_count }} Gagal
                </span>
            </div>
        </div>

        {{-- Penjelasan --}}
        <div class="px-5 py-3 bg-amber-50 border-b border-red-200 flex items-start gap-2.5">
            <i class="fas fa-info-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
            <p class="text-xs text-amber-800 leading-relaxed">
                <span class="font-semibold">Penyebab umum:</span> Dosen mengajar terlalu banyak workshop bersamaan, kapasitas ruangan tidak mencukupi, atau slot waktu sudah habis. 
                Jadwal lain tetap tersimpan — hanya {{ $jadwal->unscheduled_count }} sesi di bawah yang perlu diselesaikan manual.
            </p>
        </div>

        {{-- Tabel Detail --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-red-200 bg-red-100/40 text-left">
                        <th class="px-4 py-2.5 text-xs font-semibold text-red-700 w-8">#</th>
                        <th class="px-4 py-2.5 text-xs font-semibold text-red-700">Mata Kuliah</th>
                        <th class="px-4 py-2.5 text-xs font-semibold text-red-700 text-center">Sem</th>
                        <th class="px-4 py-2.5 text-xs font-semibold text-red-700 text-center">SKS</th>
                        <th class="px-4 py-2.5 text-xs font-semibold text-red-700">Dosen</th>
                        <th class="px-4 py-2.5 text-xs font-semibold text-red-700 text-center">Pertemuan</th>
                        <th class="px-4 py-2.5 text-xs font-semibold text-red-700">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-100">
                    @foreach($jadwal->unscheduled_items as $i => $uns)
                    <tr class="hover:bg-red-100/30 transition-colors">
                        <td class="px-4 py-2.5 text-xs text-red-400 font-medium">{{ $i + 1 }}</td>
                        <td class="px-4 py-2.5">
                            <span class="font-semibold text-red-800 text-xs">{{ $uns['namaMk'] ?? $uns['nama_mk'] }}</span>
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-200 text-red-700 text-xs font-bold">
                                {{ $uns['semester'] }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-red-100 text-red-600 text-xs font-medium">
                                {{ $uns['sks'] }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-xs text-red-700">{{ $uns['namaDosen'] ?? $uns['nama_dosen'] }}</td>
                        <td class="px-4 py-2.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ ($uns['pertemuan_ke'] ?? 1) == 1 ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700' }}">
                                Ke-{{ $uns['pertemuan_ke'] ?? '?' }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-xs text-red-600 max-w-xs">
                            <span class="flex items-start gap-1">
                                <i class="fas fa-ban text-red-400 mt-0.5 flex-shrink-0 text-xs"></i>
                                {{ $uns['alasan'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer Rekomendasi --}}
        <div class="px-5 py-3 border-t border-red-200 bg-red-50 flex flex-wrap items-center gap-x-6 gap-y-2">
            <span class="text-xs font-semibold text-red-700 flex items-center gap-1.5">
                <i class="fas fa-lightbulb text-amber-500"></i> Solusi yang disarankan:
            </span>
            <span class="text-xs text-red-600 flex items-center gap-1"><i class="fas fa-circle text-red-300 text-xs"></i> Tambah dosen pengampu untuk kelas B</span>
            <span class="text-xs text-red-600 flex items-center gap-1"><i class="fas fa-circle text-red-300 text-xs"></i> Tambah hari kuliah (misal Sabtu)</span>
            <span class="text-xs text-red-600 flex items-center gap-1"><i class="fas fa-circle text-red-300 text-xs"></i> Kurangi jumlah kelas yang diampu satu dosen</span>
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
    {{-- ─── Summary Dosen ────────────────────────────────────────────── --}}
    @if(!empty($jadwal->summary))
    <div class="mt-8">
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-user-tie text-blue-600"></i> Ringkasan Beban Dosen
        </h2>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-left">
                            <th class="px-5 py-3 font-semibold text-slate-600 w-8">#</th>
                            <th class="px-5 py-3 font-semibold text-slate-600">Nama Dosen</th>
                            <th class="px-5 py-3 font-semibold text-slate-600 text-center">SKS Teori</th>
                            <th class="px-5 py-3 font-semibold text-slate-600 text-center">SKS Praktikum</th>
                            <th class="px-5 py-3 font-semibold text-slate-600 text-center">Total SKS Ajar</th>
                            <th class="px-5 py-3 font-semibold text-slate-600 text-center">Beban SKS</th>
                            <th class="px-5 py-3 font-semibold text-slate-600 text-center">Total Sesi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($jadwal->summary as $i => $d)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-slate-400 text-xs">{{ $i + 1 }}</td>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $d['nama_dosen'] }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded bg-sky-50 text-sky-700 font-semibold text-xs">{{ $d['sks_teori'] }}</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded bg-violet-50 text-violet-700 font-semibold text-xs">{{ $d['sks_workshop'] }}</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-bold text-xs">{{ $d['sks_ajar'] }}</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                @php
                                    $beban = $d['beban_sks'];
                                    $bebanClass = $beban >= 16 ? 'bg-red-50 text-red-700' : ($beban >= 12 ? 'bg-amber-50 text-amber-700' : 'bg-green-50 text-green-700');
                                @endphp
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded {{ $bebanClass }} font-bold text-xs">{{ $beban }}</span>
                            </td>
                            <td class="px-5 py-3 text-center text-slate-600 font-medium">{{ $d['total_sesi'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 border-t border-slate-200 font-semibold text-slate-700">
                            <td colspan="2" class="px-5 py-3 text-xs text-slate-500">Total {{ count($jadwal->summary) }} dosen</td>
                            <td class="px-5 py-3 text-center text-sky-700">{{ collect($jadwal->summary)->sum('sks_teori') }}</td>
                            <td class="px-5 py-3 text-center text-violet-700">{{ collect($jadwal->summary)->sum('sks_workshop') }}</td>
                            <td class="px-5 py-3 text-center text-blue-700">{{ collect($jadwal->summary)->sum('sks_ajar') }}</td>
                            <td class="px-5 py-3 text-center">{{ number_format(collect($jadwal->summary)->sum('beban_sks'), 2) }}</td>
                            <td class="px-5 py-3 text-center">{{ collect($jadwal->summary)->sum('total_sesi') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ─── Summary Teknisi ──────────────────────────────────────────── --}}
    @if(!empty($jadwal->teknisi_summary))
    <div class="mt-6 mb-6">
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-tools text-amber-600"></i> Ringkasan Beban Teknisi
        </h2>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-left">
                            <th class="px-5 py-3 font-semibold text-slate-600 w-8">#</th>
                            <th class="px-5 py-3 font-semibold text-slate-600">Nama Teknisi</th>
                            <th class="px-5 py-3 font-semibold text-slate-600 text-center">Beban SKS Praktikum</th>
                            <th class="px-5 py-3 font-semibold text-slate-600 text-center">Total Sesi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($jadwal->teknisi_summary as $i => $t)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-slate-400 text-xs">{{ $i + 1 }}</td>
                            <td class="px-5 py-3 font-medium text-slate-800">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr($t['nama_teknisi'], 0, 1)) }}
                                    </span>
                                    {{ $t['nama_teknisi'] }}
                                </div>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-bold text-xs">{{ $t['beban_sks'] }}</span>
                            </td>
                            <td class="px-5 py-3 text-center text-slate-600 font-medium">{{ $t['total_sesi'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 border-t border-slate-200 font-semibold text-slate-700">
                            <td colspan="2" class="px-5 py-3 text-xs text-slate-500">Total {{ count($jadwal->teknisi_summary) }} teknisi</td>
                            <td class="px-5 py-3 text-center text-amber-700">{{ collect($jadwal->teknisi_summary)->sum('beban_sks') }}</td>
                            <td class="px-5 py-3 text-center">{{ collect($jadwal->teknisi_summary)->sum('total_sesi') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ─── Modal Audit Error Database ───────────────────────────────────── --}}
    @if($jadwal->unscheduled_count > 0)
    <div x-show="showModalError" 
         x-cloak
         @keydown.escape.window="showModalError = false"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        {{-- Backdrop --}}
        <div x-show="showModalError" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showModalError = false"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
            <div x-show="showModalError"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-slate-200">
                
                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-slate-900 to-red-950 px-6 py-5 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-500/20 border border-red-500/30 flex items-center justify-center text-red-400">
                            <i class="fas fa-database text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white flex items-center gap-2" id="modal-title">
                                Audit Data Error Penjadwalan
                                <span class="text-xs font-normal px-2 py-0.5 rounded bg-red-500/30 border border-red-500/40 text-red-200">
                                    DB ID: #{{ $jadwal->id }}
                                </span>
                            </h3>
                            <p class="text-xs text-slate-300 mt-0.5">Detail log error dan constraint yang tersimpan di database `jadwals.unscheduled_items`</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="showRawJson = !showRawJson" type="button" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 transition-colors">
                            <i class="fas" :class="showRawJson ? 'fa-list-ul' : 'fa-code'"></i>
                            <span x-text="showRawJson ? 'Tampilan Tabel' : 'RAW JSON DB'"></span>
                        </button>
                        <button @click="showModalError = false" type="button" 
                                class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 max-h-[70vh] overflow-y-auto bg-slate-50/50">
                    {{-- Mode RAW JSON --}}
                    <template x-if="showRawJson">
                        <div>
                            <div class="mb-3 flex justify-between items-center">
                                <span class="text-xs font-mono font-bold text-slate-500">Column: `unscheduled_items` (JSON)</span>
                                <span class="text-xs text-slate-400">Tersimpan otomatis saat `buatJadwal()` return is_success = false</span>
                            </div>
                            <pre class="bg-slate-900 text-emerald-400 p-4 rounded-xl text-xs font-mono overflow-x-auto border border-slate-800 leading-relaxed shadow-inner max-h-96"><code>{{ json_encode($jadwal->unscheduled_items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        </div>
                    </template>

                    {{-- Mode Analisis Normal --}}
                    <template x-if="!showRawJson">
                        <div class="space-y-4">
                            {{-- Ringkasan Audit Stat --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs">
                                    <div class="text-xs text-slate-500 font-medium">Total Item Gagal DB</div>
                                    <div class="text-xl font-extrabold text-red-600 mt-1">{{ $jadwal->unscheduled_count }} Sesi</div>
                                </div>
                                <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs">
                                    <div class="text-xs text-slate-500 font-medium">Tabel Database</div>
                                    <div class="text-sm font-bold text-slate-800 mt-1 font-mono">`jadwals` (ID: {{ $jadwal->id }})</div>
                                </div>
                                <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs">
                                    <div class="text-xs text-slate-500 font-medium">Status Eksekusi Algoritma</div>
                                    <div class="text-sm font-bold text-amber-600 mt-1 flex items-center gap-1.5">
                                        <i class="fas fa-exclamation-circle text-xs"></i> Overconstrained
                                    </div>
                                </div>
                            </div>

                            {{-- Cards Analisis Per Unscheduled Item --}}
                            <div class="space-y-3">
                                <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Rincian Record Constraint Gagal:</h4>
                                @foreach($jadwal->unscheduled_items as $idx => $item)
                                <div class="bg-white rounded-xl border border-red-200 p-4 shadow-xs hover:border-red-300 transition-all">
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-slate-100 pb-3 mb-3">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-6 h-6 rounded-full bg-red-100 text-red-700 flex items-center justify-center text-xs font-bold">
                                                {{ $idx + 1 }}
                                            </span>
                                            <h5 class="font-bold text-slate-900 text-sm">{{ $item['namaMk'] ?? $item['nama_mk'] }}</h5>
                                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-xs font-medium">
                                                Sem {{ $item['semester'] }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-semibold px-2 py-1 rounded bg-orange-50 text-orange-700 border border-orange-200">
                                                Pertemuan ke-{{ $item['pertemuan_ke'] ?? 1 }}
                                            </span>
                                            @if(isset($item['degree']))
                                            <span class="text-xs font-mono px-2 py-1 rounded bg-slate-100 text-slate-600">
                                                Degree: {{ $item['degree'] }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                        <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                            <span class="text-slate-400 font-medium block mb-0.5">Dosen Pengampu:</span>
                                            <span class="font-semibold text-slate-800 flex items-center gap-1.5">
                                                <i class="fas fa-user-tie text-blue-500"></i>
                                                {{ $item['namaDosen'] ?? $item['nama_dosen'] }}
                                            </span>
                                        </div>
                                        <div class="bg-red-50/70 p-2.5 rounded-lg border border-red-100">
                                            <span class="text-red-400 font-medium block mb-0.5">Alasan Konflik (DB Log):</span>
                                            <span class="font-semibold text-red-700 flex items-center gap-1.5">
                                                <i class="fas fa-ban text-red-500"></i>
                                                {{ $item['alasan'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-slate-100 px-6 py-4 border-t border-slate-200 flex justify-between items-center flex-wrap gap-2">
                    <span class="text-xs text-slate-500">
                        <i class="fas fa-database text-slate-400 mr-1"></i> Data dari `jadwals.unscheduled_items`
                    </span>
                    <button @click="showModalError = false" type="button" 
                            class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition-colors shadow-sm cursor-pointer">
                        Tutup Audit
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
</x-app-layout>
