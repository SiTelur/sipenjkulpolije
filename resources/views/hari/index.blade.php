<x-app-layout>
    <div class="flex-1 flex flex-col h-full w-full" x-data="{
        showEditModal: false,
        selectedHari: null,
        openEditModal(hari) {
            this.selectedHari = hari;
            this.showEditModal = true;
        },
        validateEditForm(event) {
            if (!this.selectedHari) return;
            const mulai = parseInt(this.selectedHari.jam_mulai);
            const selesai = parseInt(this.selectedHari.jam_selesai);
            
            if (selesai <= mulai) {
                alert('Jam selesai operasional harus lebih dari jam mulai.');
                return;
            }
            
            const mulaiIstirahat = this.selectedHari.jam_mulai_istirahat;
            const selesaiIstirahat = this.selectedHari.jam_selesai_istirahat;
            
            if (mulaiIstirahat !== '' && selesaiIstirahat === '') {
                alert('Jam selesai istirahat wajib diisi jika jam mulai istirahat ditentukan.');
                return;
            }
            if (mulaiIstirahat === '' && selesaiIstirahat !== '') {
                alert('Jam mulai istirahat wajib diisi jika jam selesai istirahat ditentukan.');
                return;
            }
            
            if (mulaiIstirahat !== '' && selesaiIstirahat !== '') {
                const startBreak = parseInt(mulaiIstirahat);
                const endBreak = parseInt(selesaiIstirahat);
                
                if (endBreak <= startBreak) {
                    alert('Jam selesai istirahat harus lebih besar dari jam mulai istirahat.');
                    return;
                }
                if (startBreak <= mulai) {
                    alert('Jam mulai istirahat harus lebih besar dari jam mulai operasional.');
                    return;
                }
                if (endBreak >= selesai) {
                    alert('Jam selesai istirahat harus kurang dari jam selesai operasional.');
                    return;
                }
            }
            
            event.target.submit();
        }
    }">
        <main class="flex-1 p-gutter overflow-y-auto">
            <div class="max-w-[1440px] mx-auto flex flex-col gap-lg">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                <div class="flex flex-col gap-xs">
                    <div class="flex items-center gap-xs font-body-sm text-body-sm text-on-surface-variant mb-2">
                        <span>Data Master</span>
                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        <span class="font-medium text-on-surface">Hari Aktif</span>
                    </div>
                    <h1 class="font-h1 text-h1 text-on-surface">Manajemen Hari Aktif</h1>
                </div>
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
                <table id="hariTable" class="w-full text-left border-collapse min-w-[500px] stripe hover" style="width:100%">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-surface-variant">
                            <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold">Hari</th>
                            <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold">Jam Operasional</th>
                            <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold">Jam Istirahat</th>
                            <th class="font-label-caps text-label-caps text-on-surface-variant py-sm px-md font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="font-data-tabular text-data-tabular text-on-surface">
                        @foreach($haris as $hari)
                        <tr class="border-b border-surface-variant transition-colors">
                            <td class="py-sm px-md font-medium">{{ $hari->nama }}</td>
                            <td class="py-sm px-md">
                                <span class="inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px] text-on-surface-variant">schedule</span>
                                    {{ sprintf('%02d:00', $hari->jam_mulai) }} - {{ sprintf('%02d:00', $hari->jam_selesai) }}
                                </span>
                            </td>
                            <td class="py-sm px-md">
                                <span class="inline-flex items-center gap-1 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[16px]">coffee</span>
                                    @if(is_null($hari->jam_mulai_istirahat) || is_null($hari->jam_selesai_istirahat))
                                        <span class="italic text-on-surface-variant/70">Tidak ada istirahat</span>
                                    @else
                                        {{ sprintf('%02d:00', $hari->jam_mulai_istirahat) }} - {{ sprintf('%02d:00', $hari->jam_selesai_istirahat) }}
                                    @endif
                                </span>
                            </td>
                            <td class="py-sm px-md text-right">
                                <button @click="openEditModal({{ json_encode($hari) }})" class="text-outline hover:text-secondary p-1 rounded hover:bg-surface-variant transition-colors"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </main>


        <!-- Edit Modal -->
        <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4" style="display: none;">
            <div @click.away="showEditModal = false" x-transition class="bg-surface-container-lowest rounded-xl shadow-xl w-full max-w-md overflow-hidden">
                <div class="px-4 py-3 border-b border-surface-variant flex justify-between items-center bg-surface">
                    <h3 class="font-h3 text-h3 text-on-surface">Edit Hari Aktif</h3>
                    <button @click="showEditModal = false" class="text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
                </div>
                <form :action="`/hari/${selectedHari?.id}`" method="POST" @submit.prevent="validateEditForm($event)">
                    @csrf
                    @method('PUT')
                    <div class="p-6 flex flex-col gap-5">
                        <div class="grid grid-cols-4 items-center gap-4">
                            <label class="text-sm font-semibold text-on-surface">Hari</label>
                            <div class="col-span-3">
                                <select name="nama" x-model="selectedHari.nama" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-md px-3 py-2 text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary outline-none transition-all pointer-events-none bg-surface-container-low opacity-80" tabindex="-1">
                                    <option value="">-- Pilih Hari --</option>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $d)
                                    <option value="{{ $d }}">{{ $d }}</option>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-on-surface-variant mt-1">Nama hari tidak dapat diubah</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-4 items-center gap-4">
                            <label class="text-sm font-semibold text-on-surface flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">schedule</span> Jam Kerja</label>
                            <div class="col-span-3 flex items-center gap-2">
                                <select name="jam_mulai" x-model="selectedHari.jam_mulai" required class="flex-1 text-sm bg-surface-container-lowest border border-outline-variant rounded-md px-2 py-2 text-on-surface focus:border-secondary focus:ring-1 outline-none">
                                    @for($i=0; $i<=23; $i++)
                                    <option value="{{ $i }}">{{ sprintf('%02d:00', $i) }}</option>
                                    @endfor
                                </select>
                                <span class="text-on-surface-variant font-medium">-</span>
                                <select name="jam_selesai" x-model="selectedHari.jam_selesai" required class="flex-1 text-sm bg-surface-container-lowest border border-outline-variant rounded-md px-2 py-2 text-on-surface focus:border-secondary focus:ring-1 outline-none">
                                    @for($i=0; $i<=23; $i++)
                                    <option value="{{ $i }}">{{ sprintf('%02d:00', $i) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-4 items-center gap-4">
                            <label class="text-sm font-semibold text-on-surface flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">coffee</span> Istirahat</label>
                            <div class="col-span-3 flex items-center gap-2">
                                <select name="jam_mulai_istirahat" x-model="selectedHari.jam_mulai_istirahat" class="flex-1 text-sm bg-surface-container-lowest border border-outline-variant rounded-md px-2 py-2 text-on-surface focus:border-secondary focus:ring-1 outline-none">
                                    <option value="">- Kosong -</option>
                                    @for($i=0; $i<=23; $i++)
                                    <option value="{{ $i }}">{{ sprintf('%02d:00', $i) }}</option>
                                    @endfor
                                </select>
                                <span class="text-on-surface-variant font-medium">-</span>
                                <select name="jam_selesai_istirahat" x-model="selectedHari.jam_selesai_istirahat" class="flex-1 text-sm bg-surface-container-lowest border border-outline-variant rounded-md px-2 py-2 text-on-surface focus:border-secondary focus:ring-1 outline-none">
                                    <option value="">- Kosong -</option>
                                    @for($i=0; $i<=23; $i++)
                                    <option value="{{ $i }}">{{ sprintf('%02d:00', $i) }}</option>
                                    @endfor
                                </select>
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


    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#hariTable').DataTable({
                responsive: true,
                ordering: false, // Disabling ordering to keep custom order from DB (Senin - Minggu)
                language: {
                    zeroRecords: "Tidak ada data yang cocok",
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
