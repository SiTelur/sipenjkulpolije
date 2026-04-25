<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::orderBy('nama')->get();
        return view('ruangan.index', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kegunaan_ruangan' => 'required|array',
            'kegunaan_ruangan.*' => 'in:TEORI,PRAKTIK'
        ]);

        Ruangan::create($validated);
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kegunaan_ruangan' => 'required|array',
            'kegunaan_ruangan.*' => 'in:TEORI,PRAKTIK'
        ]);

        $ruangan->update($validated);
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        Ruangan::findOrFail($id)->delete();
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus');
    }
}
