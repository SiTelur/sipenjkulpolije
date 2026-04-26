<x-app-layout>
    <main class="pt-6 px-4 sm:px-8 lg:px-10 pb-16">
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 mb-6 text-sm text-slate-500">
                <span>Urusan Akademik</span>
                <span class="material-symbols-outlined text-base">chevron_right</span>
                <span class="text-slate-900 font-medium">Daftar Jadwal</span>
            </div>

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end mb-8 gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-2">Daftar Jadwal Terdaftar</h1>
                    <p class="text-sm sm:text-base text-slate-500">Kelola dan audit semua jadwal akademik yang telah dibuat untuk tahun akademik ini.</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button class="w-full sm:w-auto px-4 py-2 border border-slate-200 text-blue-600 font-bold text-xs rounded-lg hover:bg-slate-50 transition-colors flex items-center justify-center gap-2 uppercase tracking-wider">
                        <span class="material-symbols-outlined text-sm">download</span>
                        EKSPOR CSV
                    </button>
                    <button onclick="window.location.href='{{ route('jadwal.generate') }}'" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white font-bold text-xs rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 uppercase tracking-wider shadow-sm">
                        <span class="material-symbols-outlined text-sm">add</span>
                        BUAT JADWAL BARU
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <!-- Table Controls -->
                <div class="p-6 border-b border-slate-100 bg-slate-50/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="relative w-full max-w-md">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                            <input class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg focus:border-blue-600 focus:ring-1 focus:ring-blue-600/20 text-sm transition-all" placeholder="Cari berdasarkan nama jadwal atau pembuat..." type="text"/>
                        </div>
                        <button class="px-4 py-2 border border-slate-200 rounded-lg flex items-center gap-2 hover:bg-slate-50 text-sm font-medium transition-colors">
                            <span class="material-symbols-outlined text-lg">filter_list</span>
                            Filter
                        </button>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-slate-500">
                        <span>Menampilkan 4 dari 12 jadwal</span>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Jadwal</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Semester</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Tanggal Dibuat</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($jadwals as $j)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-1.5 h-10 rounded-full {{ $j->is_success ? 'bg-emerald-500' : 'bg-amber-400' }} flex-shrink-0"></div>
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $j->title }}</div>
                                            <div class="text-xs text-slate-500">Dibuat oleh: Admin Utama</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">Semester {{ ucfirst($j->semester) }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $j->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($j->is_success)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Berhasil</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Gagal</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($j->is_success)
                                    <a href="{{ route('jadwal.show', $j->id) }}" class="px-3 py-1.5 bg-white border border-slate-200 text-blue-600 hover:bg-blue-50 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        DETAIL
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    Belum ada jadwal yang di-generate.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-100 bg-white">
                    {{ $jadwals->links() }}
                </div>
            </div>
    </main>
</x-app-layout>
