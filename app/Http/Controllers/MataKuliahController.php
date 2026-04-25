<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        $mataKuliahs = MataKuliah::with('pengampu')->get();
        $dosens = Dosen::where('is_active', true)->orderBy('nama')->get();
        return view('mata_kuliah.index', compact('mataKuliahs', 'dosens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:mata_kuliah,kode',
            'nama' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:8',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktek' => 'required|integer|min:0',
            'id_pengampu' => 'nullable|exists:dosen,id',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        MataKuliah::create($validated);

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata Kuliah berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:mata_kuliah,kode,' . $id,
            'nama' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:8',
            'sks_teori' => 'required|integer|min:0',
            'sks_praktek' => 'required|integer|min:0',
            'id_pengampu' => 'nullable|exists:dosen,id',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $mataKuliah->update($validated);

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata Kuliah berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        $mataKuliah->delete();

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata Kuliah berhasil dihapus');
    }
}
