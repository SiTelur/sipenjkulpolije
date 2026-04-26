<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Models\Teknisi;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosen = Dosen::count();
        $matakuliah = MataKuliah::count();
        $ruangan = Ruangan::count();
        $teknisi = Teknisi::count();
        return view('dashboard', compact('dosen', 'matakuliah', 'ruangan', 'teknisi'));
    }
}
