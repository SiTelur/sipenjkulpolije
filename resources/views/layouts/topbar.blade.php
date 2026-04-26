<header
    class="bg-white dark:bg-slate-900 font-['Public_Sans'] text-sm antialiased docked full-width border-b border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-none sticky top-0 z-40 flex h-16 w-full items-center justify-between px-4 lg:px-6">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-md transition-colors">
            <span class="material-symbols-outlined" data-icon="menu">menu</span>
        </button>
    </div>
    <div class="flex items-center gap-2">
        <button
            class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors rounded-full active:scale-95 duration-150 relative">
            <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
        </button>
        <button
            class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors rounded-full active:scale-95 duration-150">
            <span class="material-symbols-outlined" data-icon="help_outline">help_outline</span>
        </button>
        <div class="w-px h-6 bg-slate-200 dark:bg-slate-700 mx-2"></div>
        <button
            class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors rounded-full active:scale-95 duration-150 mr-2">
            <span class="material-symbols-outlined" data-icon="settings">settings</span>
        </button>
        <a href="{{ route('profile.edit') }}"
            class="block w-8 h-8 rounded-full bg-slate-200 overflow-hidden border border-slate-300 shrink-0 cursor-pointer active:scale-95 duration-150 transition-transform">
            <img alt="User Profile" class="w-full h-full object-cover"
                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=0D8ABC&color=fff" />
        </a>
    </div>
</header>