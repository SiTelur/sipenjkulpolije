<x-app-layout>
    <main class="flex-1 p-gutter overflow-y-auto" x-data="{
        showAddModal: false,
        showEditModal: false,
        showDeleteModal: false,
        selectedRuangan: null,
        openEditModal(ruangan) {
            this.selectedRuangan = ruangan;
            this.showEditModal = true;
        },
        openDeleteModal(ruangan) {
            this.selectedRuangan = ruangan;
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
                        <span class="font-medium text-on-surface">Ruangan</span>
                    </div>
                    <h1 class="font-h1 text-h1 text-on-surface">Manajemen Ruangan</h1>
                </div>
                <button @click="showAddModal=true" class="w-full sm:w-auto bg-secondary text-on-secondary hover:bg-secondary-container hover:text-on-secondary-container transition-colors duration-200 flex items-center justify-center gap-xs px-md py-sm rounded-lg font-data-tabular text-data-tabular">
                    <span class="material-symbols-outlined">add</span>
                    Tambah Ruangan
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

            <!-- Content Toolbar & Table Card -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-lg shadow-[0_4px_20px_rgba(15,23,42,0.04)] overflow-hidden p-4">
                <div class="overflow-x-auto">
                <table id="ruanganTable" class="w-full text-left border-collapse min-w-[500px] stripe hover" style="width:100%">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-surface-variant">
                            <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold">No</th>
                            <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold">Nama Ruangan</th>
                            <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold">Kegunaan Ruangan</th>
                            <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="font-data-tabular text-data-tabular text-on-surface">
                        @foreach($ruangans as $index => $r)
                        <tr class="border-b border-surface-variant transition-colors">
                            <td class="py-sm px-md font-medium">{{ $index + 1 }}</td>
                            <td class="py-sm px-md">{{ $r->nama }}</td>
                            <td class="py-sm px-md">
                                <div class="flex gap-1 flex-wrap">
                                    @foreach($r->kegunaan_ruangan as $guna)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $guna == 'TEORI' ? 'bg-primary-fixed text-on-primary-fixed border border-primary-fixed-dim' : 'bg-tertiary-fixed text-on-tertiary-fixed border border-tertiary-fixed-dim' }} text-[12px] font-medium">
                                        {{ $guna }}
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-sm px-md text-right">
                                <button @click="openEditModal({{ json_encode($r) }})" class="text-outline hover:text-secondary p-1 rounded hover:bg-surface-variant transition-colors"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                <button @click="openDeleteModal({{ json_encode($r) }})" class="text-outline hover:text-error p-1 rounded hover:bg-surface-variant transition-colors"><span class="material-symbols-outlined text-[20px]">delete</span></button>
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
            <div @click.away="showAddModal = false" x-transition class="bg-surface-container-lowest rounded-xl shadow-xl w-full max-w-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-surface-variant flex justify-between items-center bg-surface">
                    <h3 class="font-h3 text-h3 text-on-surface">Tambah Ruangan</h3>
                    <button @click="showAddModal = false" class="text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
                </div>
                <form action="{{ route('ruangan.store') }}" method="POST">
                    @csrf
                    <div class="p-4 flex flex-col gap-3">
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Nama Ruangan</label>
                            <input type="text" name="nama" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-2">Kegunaan Ruangan</label>
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="kegunaan_ruangan[]" id="guna_teori_add" value="TEORI" class="w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary">
                                    <label for="guna_teori_add" class="text-sm font-medium text-on-surface cursor-pointer">TEORI</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="kegunaan_ruangan[]" id="guna_praktik_add" value="PRAKTIK" class="w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary">
                                    <label for="guna_praktik_add" class="text-sm font-medium text-on-surface cursor-pointer">PRAKTIK</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 border-t border-surface-variant flex justify-end gap-2 bg-surface">
                        <button type="button" @click="showAddModal = false" class="px-3 py-1.5 border border-outline-variant rounded-md text-on-surface text-sm font-medium hover:bg-surface-variant transition-colors">Batal</button>
                        <button type="submit" class="px-3 py-1.5 bg-secondary text-on-secondary rounded-md text-sm font-medium hover:bg-secondary-container hover:text-on-secondary-container transition-colors">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4" style="display: none;">
            <div @click.away="showEditModal = false" x-transition class="bg-surface-container-lowest rounded-xl shadow-xl w-full max-w-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-surface-variant flex justify-between items-center bg-surface">
                    <h3 class="font-h3 text-h3 text-on-surface">Edit Ruangan</h3>
                    <button @click="showEditModal = false" class="text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
                </div>
                <form :action="`/ruangan/${selectedRuangan?.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-4 flex flex-col gap-3">
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Nama Ruangan</label>
                            <input type="text" name="nama" x-model="selectedRuangan && selectedRuangan.nama" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-2">Kegunaan Ruangan</label>
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="kegunaan_ruangan[]" id="guna_teori_edit" value="TEORI" :checked="selectedRuangan && selectedRuangan.kegunaan_ruangan && selectedRuangan.kegunaan_ruangan.includes('TEORI')" class="w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary">
                                    <label for="guna_teori_edit" class="text-sm font-medium text-on-surface cursor-pointer">TEORI</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="kegunaan_ruangan[]" id="guna_praktik_edit" value="PRAKTIK" :checked="selectedRuangan && selectedRuangan.kegunaan_ruangan && selectedRuangan.kegunaan_ruangan.includes('PRAKTIK')" class="w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary">
                                    <label for="guna_praktik_edit" class="text-sm font-medium text-on-surface cursor-pointer">PRAKTIK</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 border-t border-surface-variant flex justify-end gap-2 bg-surface">
                        <button type="button" @click="showEditModal = false" class="px-3 py-1.5 border border-outline-variant rounded-md text-on-surface text-sm font-medium hover:bg-surface-variant transition-colors">Batal</button>
                        <button type="submit" class="px-3 py-1.5 bg-secondary text-on-secondary rounded-md text-sm font-medium hover:bg-secondary-container hover:text-on-secondary-container transition-colors">Simpan</button>
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
                    <h3 class="font-h3 text-h3 text-on-surface mb-2">Hapus Ruangan?</h3>
                    <p class="text-sm text-on-surface-variant mb-6">Apakah Anda yakin ingin menghapus ruangan <strong x-text="selectedRuangan?.nama"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                    
                    <form :action="`/ruangan/${selectedRuangan?.id}`" method="POST" class="flex gap-2 justify-center">
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
            $('#ruanganTable').DataTable({
                responsive: true,
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
        });
    </script>
    @endpush
</x-app-layout>
