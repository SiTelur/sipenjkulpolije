<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Enums\DosenType;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        $dosens = Dosen::orderBy('id', 'desc')->get();
        return view('dosen.index', compact('dosens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nidn' => 'required|string|max:20|unique:dosen,nidn',
            'tipe_dosen' => 'required|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Dosen::create($validated);

        return redirect()->route('dosen.index')->with('success', 'Data Dosen berhasil ditambahkan');
    }

    public function update(Request $request, Dosen $dosen)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nidn' => 'required|string|max:20|unique:dosen,nidn,' . $dosen->id,
            'tipe_dosen' => 'required|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $dosen->update($validated);

        return redirect()->route('dosen.index')->with('success', 'Data Dosen berhasil diupdate');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->delete();
        return redirect()->route('dosen.index')->with('success', 'Data Dosen berhasil dihapus');
    }
}
