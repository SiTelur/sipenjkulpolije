<x-app-layout>
    <main class="pt-6 px-4 sm:px-8 lg:px-10 pb-12">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-slate-500 font-body-sm mb-6">
            <span class="hover:text-blue-600 cursor-pointer text-sm">Urusan Akademik</span>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-slate-900 font-medium text-sm">Buat Jadwal</span>
        </nav>

        <header class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-1">Buat Jadwal Akademik Baru</h1>
            <p class="text-slate-500 text-base">Konfigurasi parameter dan pratinjau data sebelum jadwal digenerate secara otomatis.</p>
        </header>

        <div class="grid grid-cols-12 gap-8" x-data="{
            tab: 'courses',
            jenis: '{{ $jenis ?? '' }}',
            warnings: [],
            get canGenerate() {
                return this.warnings.length === 0;
            },
            init() {
                this.$nextTick(() => {
                    initDataTables(this.jenis);
                });
            },
            reloadTables() {
                if (window._dtList) {
                    window._dtList.forEach(dt => {
                        let id = dt.table().node().id.replace('dt-', '');
                        dt.ajax.url('{{ url('jadwal/api/preview') }}/' + id + '?jenis=' + this.jenis).load();
                    });
                }
            }
        }" @update-warnings.window="warnings = $event.detail">
            <!-- Left: Configuration Column -->
            <form action="{{ route('jadwal.store') }}" method="POST" class="col-span-12 lg:col-span-4 space-y-6">
                @csrf

                <!-- Konfigurasi Dasar -->
                <section class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <h2 class="text-xs font-bold text-slate-400 mb-5 uppercase tracking-wider">Konfigurasi Dasar</h2>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Jadwal</label>
                            <input name="title" required class="w-full border border-slate-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-600 outline-none transition-all text-sm" placeholder="cth: Ganjil 2024/2025 – TI" type="text"/>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Semester</label>
                            <select
                                name="semester"
                                class="w-full border border-slate-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-600 outline-none transition-all text-sm bg-white"
                                x-model="jenis"
                                @change="reloadTables()"
                            >
                                <option value="ganjil">Semester Ganjil (1, 3, 5)</option>
                                <option value="genap">Semester Genap (2, 4, 6)</option>
                            </select>
                        </div>
                    </div>
                </section>

                <!-- Override Jam Praktikum -->
                <section class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm" x-data="{ override: false }">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Override Jam Praktikum</h2>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="override_praktikum" value="0">
                            <input name="override_praktikum" value="1" class="sr-only peer" type="checkbox" x-model="override"/>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div x-show="override" x-transition class="p-4 bg-slate-50 rounded-lg border border-slate-100">
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-1 uppercase">Durasi (Jam)</label>
                                <div class="flex items-center bg-white border border-slate-200 rounded-lg px-2 py-2">
                                    <span class="material-symbols-outlined text-[18px] text-slate-400 mr-2">schedule</span>
                                    <input name="durasi_praktikum" class="border-none p-0 focus:ring-0 text-sm w-full font-medium bg-transparent" type="number" value="3" min="1" max="8"/>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 text-[11px] text-slate-400 italic flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">info</span>
                            Durasi praktikum akan diset menjadi 3 jam.
                        </p>
                    </div>
                    <div x-show="!override" class="text-xs text-slate-400 italic">Jam mengikuti konfigurasi per-hari yang terdaftar.</div>
                </section>

                <!-- Tombol Aksi -->
                <div class="flex flex-col gap-3">
                    <button type="submit" :disabled="!canGenerate" :class="!canGenerate ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200'" class="w-full text-white py-3.5 rounded-xl font-bold flex items-center justify-center gap-2 transition-all text-sm">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
                        Generate Jadwal
                    </button>
                    <a href="{{ route('jadwal.list') }}" class="w-full bg-white text-slate-600 border border-slate-200 py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-slate-50 transition-colors text-sm">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Kembali ke Daftar
                    </a>
                </div>
            </form>

            <!-- Right: Preview Column with Tabs -->
            <div class="col-span-12 lg:col-span-8">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col overflow-hidden" style="min-height: 580px;">

                    <!-- Preview Header -->
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600 text-[20px]">table_view</span>
                            <h3 class="text-base font-semibold text-slate-900">Pratinjau Data</h3>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <span class="material-symbols-outlined text-[14px]">info</span>
                            Data diambil langsung dari database
                        </div>
                    </div>

                    <!-- Tab Navigation (5 tabs) -->
                    <div class="flex border-b border-slate-100 bg-white overflow-x-auto">
                        @php
                            $tabs = [
                                ['key'=>'courses',  'icon'=>'menu_book',      'label'=>'Mata Kuliah'],
                                ['key'=>'faculty',  'icon'=>'person',         'label'=>'Dosen'],
                                ['key'=>'rooms',    'icon'=>'meeting_room',   'label'=>'Ruangan'],
                                ['key'=>'days',     'icon'=>'calendar_month', 'label'=>'Hari'],
                                ['key'=>'teknisi',  'icon'=>'construction',   'label'=>'Teknisi'],
                            ];
                        @endphp
                        @foreach($tabs as $t)
                        <button
                            class="flex-1 py-3.5 text-xs font-bold transition-all flex items-center justify-center gap-1.5 border-b-2 whitespace-nowrap"
                            :class="tab === '{{ $t['key'] }}' ? 'text-blue-600 border-blue-600 bg-slate-50' : 'text-slate-500 border-transparent hover:text-slate-900'"
                            @click="tab = '{{ $t['key'] }}'; setTimeout(() => window._dtList.forEach(dt => dt.columns.adjust()), 100)"
                        >
                            <span class="material-symbols-outlined text-[16px]">{{ $t['icon'] }}</span>
                            {{ $t['label'] }}
                        </button>
                        @endforeach
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6 flex-1 overflow-y-auto">

                        {{-- ===== TAB: MATA KULIAH ===== --}}
                        <div x-show="tab === 'courses'">
                            <table id="dt-matakuliah" class="w-full text-sm" style="width:100%"></table>
                        </div>

                        {{-- ===== TAB: DOSEN ===== --}}
                        <div x-show="tab === 'faculty'" style="display:none;">
                            <table id="dt-dosen" class="w-full text-sm" style="width:100%"></table>
                        </div>

                        {{-- ===== TAB: RUANGAN ===== --}}
                        <div x-show="tab === 'rooms'" style="display:none;">
                            <table id="dt-ruangan" class="w-full text-sm" style="width:100%"></table>
                        </div>

                        {{-- ===== TAB: HARI ===== --}}
                        <div x-show="tab === 'days'" style="display:none;">
                            <table id="dt-hari" class="w-full text-sm" style="width:100%"></table>
                        </div>

                        {{-- ===== TAB: TEKNISI ===== --}}
                        <div x-show="tab === 'teknisi'" style="display:none;">
                            <table id="dt-teknisi" class="w-full text-sm" style="width:100%"></table>
                        </div>

                    </div>

                    <!-- Footer: Summary Info -->
                    <div class="px-6 pb-5">
                        <div x-show="canGenerate" class="p-4 bg-blue-50/60 rounded-xl border border-blue-100 flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-600 mt-0.5 text-[20px]">info</span>
                            <div>
                                <p class="text-[12px] font-semibold text-blue-900 mb-0.5">Informasi Pratinjau</p>
                                <p class="text-[11px] text-blue-700 leading-relaxed">
                                    Data pada tabel di atas memuat daftar entitas yang akan digunakan dalam generate jadwal. Pastikan semua data sudah sesuai dengan semester yang dipilih.
                                </p>
                            </div>
                        </div>

                        <div x-cloak x-show="!canGenerate" class="p-4 bg-red-50/60 rounded-xl border border-red-100 flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-600 mt-0.5 text-[20px]">warning</span>
                            <div class="w-full">
                                <p class="text-[12px] font-semibold text-red-900 mb-1">Terdapat Data Tidak Lengkap</p>
                                <ul class="text-[11px] text-red-700 leading-relaxed list-disc list-inside">
                                    <template x-for="warning in warnings" :key="warning">
                                        <li x-text="warning"></li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        function initDataTables(jenis) {
            window._dtList = [];

            let commonOptions = {
                responsive: true,
                pageLength: 10,
                lengthChange: false,
                language: {
                    search: "Cari:",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Data tidak ditemukan",
                    emptyTable: "Pilih semester untuk melihat pratinjau data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            };

            let baseUrl = '{{ url("jadwal/api/preview") }}';
            let getUrl = (type) => baseUrl + '/' + type + '?jenis=' + jenis;

            let dtMk = new DataTable('#dt-matakuliah', {
                ...commonOptions,
                ajax: getUrl('matakuliah'),
                columns: [
                    { data: 'kode', title: 'Kode' },
                    { data: 'nama', title: 'Mata Kuliah' },
                    { data: 'semester', title: 'Smt' },
                    { data: 'sks', title: 'SKS' },
                    { data: 'tipe', title: 'Tipe' },
                    { data: 'pengampu', title: 'Dosen Pengampu' }
                ]
            });

            dtMk.on('xhr.dt', function (e, settings, json, xhr) {
                if(json && json.data) {
                    let missing = json.data.filter(mk => mk.pengampu === '–' || mk.pengampu === null);
                    let msgs = missing.map(mk => `Mata Kuliah ${mk.nama} belum memiliki Dosen Pengampu.`);
                    document.dispatchEvent(new CustomEvent('update-warnings', { detail: msgs }));
                }
            });

            let dtDsn = new DataTable('#dt-dosen', {
                ...commonOptions,
                ajax: getUrl('dosen'),
                columns: [
                    { data: 'nama', title: 'Nama Dosen' },
                    { data: 'nidn', title: 'NIDN/NIP' },
                    { data: 'tipe', title: 'Tipe' },
                    { data: 'matkul', title: 'Mata Kuliah' }
                ]
            });

            let dtRgn = new DataTable('#dt-ruangan', {
                ...commonOptions,
                ajax: getUrl('ruangan'),
                columns: [
                    { data: 'nama', title: 'Nama Ruangan' },
                    { data: 'kegunaan', title: 'Kegunaan' }
                ]
            });

            let dtHari = new DataTable('#dt-hari', {
                ...commonOptions,
                ajax: getUrl('hari'),
                columns: [
                    { data: 'nama', title: 'Hari' },
                    { data: 'waktu', title: 'Waktu Aktif' },
                    { data: 'istirahat', title: 'Istirahat' }
                ]
            });

            let dtTek = new DataTable('#dt-teknisi', {
                ...commonOptions,
                ajax: getUrl('teknisi'),
                columns: [
                    { data: 'nama', title: 'Nama Teknisi' },
                    { data: 'status', title: 'Status' }
                ]
            });

            window._dtList.push(dtMk, dtDsn, dtRgn, dtHari, dtTek);
        }
    </script>
    @endpush
</x-app-layout>
