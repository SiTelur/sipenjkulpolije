<?php

namespace App\Http\Controllers;

use App\Models\Teknisi;
use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    public function index()
    {
        $teknisis = Teknisi::orderBy('nama')->get();
        return view('teknisi.index', compact('teknisis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        Teknisi::create($validated);

        return redirect()->route('teknisi.index')->with('success', 'Teknisi berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $teknisi = Teknisi::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $teknisi->update($validated);

        return redirect()->route('teknisi.index')->with('success', 'Teknisi berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        Teknisi::findOrFail($id)->delete();
        return redirect()->route('teknisi.index')->with('success', 'Teknisi berhasil dihapus');
    }
}
