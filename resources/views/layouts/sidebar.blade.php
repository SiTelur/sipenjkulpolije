<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="lg:translate-x-0 bg-slate-50 dark:bg-slate-950 font-['Public_Sans'] text-sm font-medium docked h-screen w-64 border-r border-slate-200 dark:border-slate-800 flat no shadows transition-transform duration-300 ease-in-out fixed left-0 top-0 flex flex-col p-4 gap-2 z-50">
    <!-- Header -->
    <div class="px-3 py-4 mb-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-blue-600" data-icon="account_balance">account_balance</span>
        </div>
        <div class="flex flex-col">
            <span class="text-xl font-black text-slate-900 dark:text-slate-50 tracking-tight leading-tight">Sistem Penjadwalan</span>
            <span class="text-xs text-slate-500 font-normal">Perkuliahan</span>
        </div>
    </div>
    @php
        $activeClass = 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-sm ring-1 ring-slate-200 dark:ring-slate-800';
        $inactiveClass = 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors';
    @endphp

    <nav class="flex-1 flex flex-col gap-1">
        <!-- Dashboard -->
        <a class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined" data-icon="dashboard" {!! request()->routeIs('dashboard') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>dashboard</span>
            <span class="{{ request()->routeIs('dashboard') ? 'font-semibold' : '' }}">Dashboard</span>
        </a>
        
        <!-- Schedules -->
        <a class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('jadwal.list') ? $activeClass : $inactiveClass }}" href="{{ route('jadwal.list') }}">
            <span class="material-symbols-outlined" data-icon="calendar_month" {!! request()->routeIs('jadwal.list') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>calendar_month</span>
            <span class="{{ request()->routeIs('jadwal.list') ? 'font-semibold' : '' }}">Jadwal</span>
        </a>

        <!-- Generate Jadwal -->
        <a class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('jadwal.generate') ? $activeClass : $inactiveClass }}" href="{{ route('jadwal.generate') }}">
            <span class="material-symbols-outlined" data-icon="auto_awesome" {!! request()->routeIs('jadwal.generate') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>auto_awesome</span>
            <span class="{{ request()->routeIs('jadwal.generate') ? 'font-semibold' : '' }}">Generate Jadwal</span>
        </a>


        <!-- Master Data Group -->
        <div class="px-3 mt-4 mb-1 text-xs font-semibold text-slate-500 uppercase tracking-wider">Data Master</div>
        
        <a class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('dosen.*') ? $activeClass : $inactiveClass }}" href="{{ route('dosen.index') }}">
            <span class="material-symbols-outlined" data-icon="groups" {!! request()->routeIs('dosen.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>groups</span>
            <span class="{{ request()->routeIs('dosen.*') ? 'font-semibold' : '' }}">Dosen</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('mata-kuliah.*') ? $activeClass : $inactiveClass }}" href="{{ route('mata-kuliah.index') }}">
            <span class="material-symbols-outlined" data-icon="menu_book" {!! request()->routeIs('mata-kuliah.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>menu_book</span>
            <span class="{{ request()->routeIs('mata-kuliah.*') ? 'font-semibold' : '' }}">Mata Kuliah</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('teknisi.*') ? $activeClass : $inactiveClass }}" href="{{ route('teknisi.index') }}">
            <span class="material-symbols-outlined" data-icon="construction" {!! request()->routeIs('teknisi.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>construction</span>
            <span class="{{ request()->routeIs('teknisi.*') ? 'font-semibold' : '' }}">Teknisi</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('ruangan.*') ? $activeClass : $inactiveClass }}" href="{{ route('ruangan.index') }}">
            <span class="material-symbols-outlined" data-icon="meeting_room" {!! request()->routeIs('ruangan.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>meeting_room</span>
            <span class="{{ request()->routeIs('ruangan.*') ? 'font-semibold' : '' }}">Ruangan</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('hari.*') ? $activeClass : $inactiveClass }}" href="{{ route('hari.index') }}">
            <span class="material-symbols-outlined" data-icon="today" {!! request()->routeIs('hari.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>today</span>
            <span class="{{ request()->routeIs('hari.*') ? 'font-semibold' : '' }}">Hari</span>
        </a>
    </nav>
    <!-- Footer Tabs -->
    <div class="mt-auto pt-4 border-t border-slate-200 dark:border-slate-800 flex flex-col gap-1">
        <a class="flex items-center gap-3 px-3 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors" href="#">
            <span class="material-symbols-outlined" data-icon="contact_support">contact_support</span>
            <span>Bantuan</span>
        </a>
        
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors">
                <span class="material-symbols-outlined" data-icon="logout">logout</span>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
