<x-app-layout>
    <main class="flex-1 p-gutter overflow-y-auto">
        <div class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
            <!-- Page Header -->
            <div class="col-span-1 md:col-span-2 lg:col-span-4 flex items-end justify-between">
                <div class="flex flex-col gap-xs">
                    <h1 class="font-h1 text-h1 text-on-surface">Ringkasan</h1>
                    <p class="font-body-base text-body-base text-on-surface-variant">Kelola sumber daya penjadwalan
                        universitas dan aktivitas hari ini.</p>
                </div>
                <div class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, j F Y') }}
                </div>
            </div>
            <!-- Quick Actions (Moved below header) -->
            <div
                class="col-span-1 md:col-span-2 lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col sm:flex-row sm:items-center justify-between gap-md shadow-sm">
                <h3 class="font-h3 text-h3 text-on-surface">Aksi Cepat</h3>
                <div class="flex flex-col sm:flex-row gap-sm w-full sm:w-auto">
                    <button onclick="window.location.href='{{ route('jadwal.generate') }}'"
                        class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-secondary text-on-secondary font-body-base font-medium px-4 py-2.5 rounded-lg hover:bg-on-secondary-fixed-variant transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                        Buat Jadwal Baru
                    </button>

                </div>
            </div>
            <!-- Metric Card 1 -->
            <div
                class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col gap-base shadow-sm relative overflow-hidden group hover:border-secondary transition-colors">
                <div
                    class="absolute right-0 top-0 w-24 h-24 bg-primary-fixed opacity-20 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110 duration-300">
                </div>
                <div class="flex justify-between items-start z-10">
                    <span class="font-label-caps text-label-caps text-on-surface-variant uppercase">Total Dosen</span>
                </div>
                <div class="font-h1 text-h1 text-on-surface z-10 mt-2">{{ $dosen }}</div>
            </div>
            <!-- Metric Card 2 -->
            <div
                class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col gap-base shadow-sm relative overflow-hidden group hover:border-secondary transition-colors">
                <div
                    class="absolute right-0 top-0 w-24 h-24 bg-tertiary-fixed opacity-20 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110 duration-300">
                </div>
                <div class="flex justify-between items-start z-10">
                    <span class="font-label-caps text-label-caps text-on-surface-variant uppercase">Mata Kuliah
                        Aktif</span>
                </div>
                <div class="font-h1 text-h1 text-on-surface z-10 mt-2">{{ $matakuliah }}</div>
            </div>
            <!-- Metric Card 3 -->
            <div
                class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col gap-base shadow-sm relative overflow-hidden group hover:border-secondary transition-colors">
                <div
                    class="absolute right-0 top-0 w-24 h-24 bg-surface-container-highest opacity-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110 duration-300">
                </div>
                <div class="flex justify-between items-start z-10">
                    <span class="font-label-caps text-label-caps text-on-surface-variant uppercase">Total Ruangan</span>
                </div>
                <div class="font-h1 text-h1 text-on-surface z-10 mt-2">{{ $ruangan }}</div>
            </div>
            <!-- Metric Card 4 -->
            <div
                class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col gap-base shadow-sm relative overflow-hidden group hover:border-secondary transition-colors">
                <div
                    class="absolute right-0 top-0 w-24 h-24 bg-secondary-fixed-dim opacity-30 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110 duration-300">
                </div>
                <div class="flex justify-between items-start z-10">
                    <span class="font-label-caps text-label-caps text-on-surface-variant uppercase">Total Teknisi</span>
                </div>
                <div class="font-h1 text-h1 text-on-surface z-10 mt-2">{{ $teknisi }}</div>
            </div>
        </div>
    </main>
</x-app-layout>