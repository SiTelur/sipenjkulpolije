<x-app-layout>
    <main class="flex-1 p-gutter overflow-y-auto" x-data="{
        showAddModal: false,
        showEditModal: false,
        showDeleteModal: false,
        selectedMK: null,
        openEditModal(mk) {
            this.selectedMK = mk;
            this.showEditModal = true;
        },
        openDeleteModal(mk) {
            this.selectedMK = mk;
            this.showDeleteModal = true;
        }
    }">
        <div class="max-w-[1440px] mx-auto flex flex-col gap-lg">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                <div class="flex flex-col gap-xs">
                    <div class="flex items-center gap-xs font-body-sm text-body-sm text-on-surface-variant mb-2">
                        <span>Data Master</span>
                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        <span class="font-medium text-on-surface">Mata Kuliah</span>
                    </div>
                    <h1 class="font-h1 text-h1 text-on-surface">Manajemen Mata Kuliah</h1>
                </div>
                <button @click="showAddModal=true" class="w-full sm:w-auto bg-secondary text-on-secondary hover:bg-secondary-container hover:text-on-secondary-container transition-colors duration-200 flex items-center justify-center gap-xs px-md py-sm rounded-lg font-data-tabular text-data-tabular">
                    <span class="material-symbols-outlined">add</span>
                    Tambah Mata Kuliah
                </button>
            </div>

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Filter & Table Card -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-lg shadow-[0_4px_20px_rgba(15,23,42,0.04)] overflow-hidden p-4">
                
                <!-- Filter wrapper disembunyikan sementara, akan dipindah oleh DataTables -->
                <div id="customFilterWrapper" class="hidden items-center gap-2">
                    <label class="text-sm font-medium text-on-surface whitespace-nowrap">Filter Semester:</label>
                    <select id="semesterFilter" class="bg-surface border border-outline-variant rounded-md pl-3 pr-8 py-1 text-sm text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all cursor-pointer">
                        <option value="semua">Semua Semester</option>
                        <option value="ganjil">Semester Ganjil</option>
                        <option value="genap">Semester Genap</option>
                    </select>
                </div>

                <div class="overflow-x-auto">
                    <table id="mkTable" class="w-full text-left border-collapse min-w-[700px] stripe hover" style="width:100%">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-surface-variant">
                                <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold">Kode MK</th>
                                <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold">Nama</th>
                                <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold">Semester</th>
                                <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold">Kelas</th>
                                <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold">SKS (T/P)</th>
                                <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold">Dosen Pengampu</th>
                                <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold">Status</th>
                                <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="font-data-tabular text-data-tabular text-on-surface">
                            @foreach($mataKuliahs as $mk)
                            <tr class="border-b border-surface-variant transition-colors">
                                <td class="py-sm px-md font-medium">{{ $mk->kode }}</td>
                                <td class="py-sm px-md">{{ $mk->nama }}</td>
                                <td class="py-sm px-md text-on-surface-variant">Semester {{ $mk->semester }}</td>
                                <td class="py-sm px-md text-on-surface-variant">{{ $mk->kelas }}</td>
                                <td class="py-sm px-md text-on-surface-variant text-center">{{ $mk->sks_teori }} / {{ $mk->sks_praktek }}</td>
                                <td class="py-sm px-md">
                                    @if($mk->pengampu)
                                    <div class="flex items-center gap-xs">
                                        {{ $mk->pengampu->nama }}
                                    </div>
                                    @else
                                    <span class="text-outline italic">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td class="py-sm px-md">
                                    @if($mk->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed text-[12px] font-medium border border-primary-fixed-dim">
                                        <span class="w-1.5 h-1.5 rounded-full bg-secondary mr-1.5"></span>
                                        Aktif
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-variant text-on-surface-variant text-[12px] font-medium border border-outline-variant">
                                        <span class="w-1.5 h-1.5 rounded-full bg-outline mr-1.5"></span>
                                        Tidak Aktif
                                    </span>
                                    @endif
                                </td>
                                <td class="py-sm px-md text-right">
                                    <button @click="openEditModal({{ json_encode($mk) }})" class="text-outline hover:text-secondary p-1 rounded hover:bg-surface-variant transition-colors"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                    <button @click="openDeleteModal({{ json_encode($mk) }})" class="text-outline hover:text-error p-1 rounded hover:bg-surface-variant transition-colors"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Modal -->
        <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4" style="display: none;">
            <div @click.away="showAddModal = false" x-transition class="bg-surface-container-lowest rounded-xl shadow-xl w-full max-w-md overflow-hidden">
                <div class="px-5 py-3 border-b border-surface-variant flex justify-between items-center bg-surface">
                    <h3 class="font-h3 text-h3 text-on-surface">Tambah Mata Kuliah</h3>
                    <button @click="showAddModal = false" class="text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
                </div>
                <form action="{{ route('mata-kuliah.store') }}" method="POST">
                    @csrf
                    <div class="p-5 flex flex-col gap-4 max-h-[70vh] overflow-y-auto">
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Kode MK</label>
                            <input type="text" name="kode" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Nama Mata Kuliah</label>
                            <input type="text" name="nama" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-on-surface mb-1">Semester</label>
                                <input type="number" name="semester" min="1" max="8" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-on-surface mb-1">Kelas</label>
                                <input type="text" name="kelas" class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-on-surface mb-1">SKS Teori</label>
                                <input type="number" name="sks_teori" min="0" value="0" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-on-surface mb-1">SKS Praktek</label>
                                <input type="number" name="sks_praktek" min="0" value="0" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Dosen Pengampu (Opsional)</label>
                            <select name="id_pengampu" class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosens as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <input type="checkbox" name="is_active" id="is_active_add" value="1" checked class="w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary">
                            <label for="is_active_add" class="text-sm font-medium text-on-surface cursor-pointer">Status Aktif</label>
                        </div>
                    </div>
                    <div class="px-5 py-3 border-t border-surface-variant flex justify-end gap-3 bg-surface">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 border border-outline-variant rounded-md text-on-surface text-sm font-medium hover:bg-surface-variant transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-secondary text-on-secondary rounded-md text-sm font-medium hover:bg-secondary-container hover:text-on-secondary-container transition-colors">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4" style="display: none;">
            <div @click.away="showEditModal = false" x-transition class="bg-surface-container-lowest rounded-xl shadow-xl w-full max-w-md overflow-hidden">
                <div class="px-5 py-3 border-b border-surface-variant flex justify-between items-center bg-surface">
                    <h3 class="font-h3 text-h3 text-on-surface">Edit Mata Kuliah</h3>
                    <button @click="showEditModal = false" class="text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
                </div>
                <form :action="`/mata-kuliah/${selectedMK?.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-5 flex flex-col gap-4 max-h-[70vh] overflow-y-auto">
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Kode MK</label>
                            <input type="text" name="kode" x-model="selectedMK && selectedMK.kode" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Nama Mata Kuliah</label>
                            <input type="text" name="nama" x-model="selectedMK && selectedMK.nama" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-on-surface mb-1">Semester</label>
                                <input type="number" name="semester" x-model="selectedMK && selectedMK.semester" min="1" max="8" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-on-surface mb-1">Kelas</label>
                                <input type="text" name="kelas" x-model="selectedMK && selectedMK.kelas" class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-on-surface mb-1">SKS Teori</label>
                                <input type="number" name="sks_teori" x-model="selectedMK && selectedMK.sks_teori" min="0" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-on-surface mb-1">SKS Praktek</label>
                                <input type="number" name="sks_praktek" x-model="selectedMK && selectedMK.sks_praktek" min="0" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Dosen Pengampu</label>
                            <select name="id_pengampu" x-model="selectedMK && selectedMK.id_pengampu" class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosens as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <input type="checkbox" name="is_active" id="is_active_edit" value="1" :checked="selectedMK?.is_active" class="w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary">
                            <label for="is_active_edit" class="text-sm font-medium text-on-surface cursor-pointer">Status Aktif</label>
                        </div>
                    </div>
                    <div class="px-5 py-3 border-t border-surface-variant flex justify-end gap-3 bg-surface">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 border border-outline-variant rounded-md text-on-surface text-sm font-medium hover:bg-surface-variant transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-secondary text-on-secondary rounded-md text-sm font-medium hover:bg-secondary-container hover:text-on-secondary-container transition-colors">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4" style="display: none;">
            <div @click.away="showDeleteModal = false" x-transition class="bg-surface-container-lowest rounded-xl shadow-xl w-full max-w-sm overflow-hidden">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-error-container text-error flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-[32px]">warning</span>
                    </div>
                    <h3 class="font-h3 text-h3 text-on-surface mb-2">Hapus Mata Kuliah?</h3>
                    <p class="text-sm text-on-surface-variant mb-6">Apakah Anda yakin ingin menghapus mata kuliah <strong x-text="selectedMK?.nama"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                    
                    <form :action="`/mata-kuliah/${selectedMK?.id}`" method="POST" class="flex gap-2 justify-center">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="showDeleteModal = false" class="flex-1 px-3 py-1.5 border border-outline-variant rounded-md text-on-surface text-sm font-medium hover:bg-surface-variant transition-colors">Batal</button>
                        <button type="submit" class="flex-1 px-3 py-1.5 bg-error text-on-error rounded-md text-sm font-medium hover:bg-error hover:text-white transition-colors shadow-sm">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Fungsi filter kustom untuk DataTables berdasarkan Semester Ganjil / Genap
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var filter = $('#semesterFilter').val();
                if (!filter || filter === 'semua') return true;
                
                // Index 2 adalah kolom "Semester" (misal: "Semester 1")
                var semesterText = data[2] || ""; 
                var match = semesterText.match(/\d+/);
                
                if (match) {
                    var sem = parseInt(match[0], 10);
                    if (filter === 'ganjil') return sem % 2 !== 0;
                    if (filter === 'genap') return sem % 2 === 0;
                }
                return true;
            });

            var table = $('#mkTable').DataTable({
                responsive: true,
                dom: '<"flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4"<"flex items-center gap-4"l<"#filter-container">>f>rt<"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4"ip>',
                initComplete: function() {
                    // Pindahkan dropdown filter kita ke dalam container yang sudah disiapkan di DOM DataTables
                    $('#customFilterWrapper').detach().appendTo('#filter-container');
                    $('#customFilterWrapper').removeClass('hidden').addClass('flex');
                },
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            // Update tabel setiap kali dropdown filter berubah
            $('#semesterFilter').on('change', function() {
                table.draw();
            });
        });
    </script>
    @endpush
</x-app-layout>
