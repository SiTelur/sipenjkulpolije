<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\HariController;
use App\Http\Controllers\JadwalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('dosen', DosenController::class);
    Route::resource('mata-kuliah', MataKuliahController::class);
    Route::resource('teknisi', TeknisiController::class);
    Route::resource('ruangan', RuanganController::class);
    Route::resource('hari', HariController::class);
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.list');
    Route::get('/jadwal/api/preview/{type}', [JadwalController::class, 'preview'])->name('jadwal.api.preview');
    Route::get('/jadwal/generate', [JadwalController::class, 'create'])->name('jadwal.generate');
    Route::post('/jadwal/generate', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::get('/jadwal/{id}', [JadwalController::class, 'show'])->name('jadwal.show');
    Route::get('/jadwal/{id}/export', [JadwalController::class, 'export'])->name('jadwal.export');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
