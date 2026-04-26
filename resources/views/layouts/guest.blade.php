<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Penjadwalan Kuliah Polije') }}</title>

        <link rel="icon" href="{{ asset('logo.png') }}">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com" rel="preconnect">
        <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Public Sans', sans-serif; }
        </style>
    </head>
    <body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">

        <div class="w-full max-w-sm">
            <!-- Logo & Branding -->
            <div class="flex flex-col items-center mb-8">
                <img src="{{ asset('logo.png') }}" alt="Logo Polije" class="h-20 w-20 object-contain mb-4">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">SiPenjKul Polije</h1>
                <p class="text-sm text-slate-500 mt-1">Sistem Penjadwalan Kuliah</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <h2 class="text-base font-bold text-slate-900">Masuk ke Akun Anda</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Masukkan email dan password untuk melanjutkan</p>
                </div>
                <div class="px-6 py-6">
                    {{ $slot }}
                </div>
            </div>

            <p class="text-center text-xs text-slate-400 mt-6">
                &copy; {{ date('Y') }} Politeknik Negeri Jember. Hak cipta dilindungi.
            </p>
        </div>
    </body>
</html>
