<?php

namespace App\Http\Controllers;

use App\Models\Hari;
use Illuminate\Http\Request;

class HariController extends Controller
{
    public function index()
    {
        $haris = Hari::orderByRaw("
            CASE nama 
                WHEN 'Senin' THEN 1
                WHEN 'Selasa' THEN 2
                WHEN 'Rabu' THEN 3
                WHEN 'Kamis' THEN 4
                WHEN 'Jumat' THEN 5
                WHEN 'Sabtu' THEN 6
                WHEN 'Minggu' THEN 7
            END
        ")->get();
        return view('hari.index', compact('haris'));
    }

    public function store(Request $request)
    {
        $messages = [
            'jam_selesai.gt' => 'Jam selesai operasional harus lebih dari jam mulai.',
            'jam_mulai_istirahat.required_with' => 'Jam mulai istirahat wajib diisi jika jam selesai istirahat ditentukan.',
            'jam_selesai_istirahat.required_with' => 'Jam selesai istirahat wajib diisi jika jam mulai istirahat ditentukan.',
            'jam_selesai_istirahat.gt' => 'Jam selesai istirahat harus lebih besar dari jam mulai istirahat.',
            'jam_mulai_istirahat.gt' => 'Jam mulai istirahat harus lebih besar dari jam mulai operasional.',
            'jam_mulai_istirahat.lt' => 'Jam mulai istirahat harus kurang dari jam selesai operasional.',
            'jam_selesai_istirahat.lt' => 'Jam selesai istirahat harus kurang dari jam selesai operasional.',
        ];

        $validated = $request->validate([
            'nama' => 'required|string|max:20|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|integer|min:0|max:23',
            'jam_selesai' => 'required|integer|min:0|max:23|gt:jam_mulai',
            'jam_mulai_istirahat' => 'nullable|integer|min:0|max:23|required_with:jam_selesai_istirahat|gt:jam_mulai|lt:jam_selesai',
            'jam_selesai_istirahat' => 'nullable|integer|min:0|max:23|required_with:jam_mulai_istirahat|gt:jam_mulai_istirahat|lt:jam_selesai',
        ], $messages);

        Hari::create($validated);
        return redirect()->route('hari.index')->with('success', 'Data Hari berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $hari = Hari::findOrFail($id);
        $messages = [
            'jam_selesai.gt' => 'Jam selesai operasional harus lebih dari jam mulai.',
            'jam_mulai_istirahat.required_with' => 'Jam mulai istirahat wajib diisi jika jam selesai istirahat ditentukan.',
            'jam_selesai_istirahat.required_with' => 'Jam selesai istirahat wajib diisi jika jam mulai istirahat ditentukan.',
            'jam_selesai_istirahat.gt' => 'Jam selesai istirahat harus lebih besar dari jam mulai istirahat.',
            'jam_mulai_istirahat.gt' => 'Jam mulai istirahat harus lebih besar dari jam mulai operasional.',
            'jam_mulai_istirahat.lt' => 'Jam mulai istirahat harus kurang dari jam selesai operasional.',
            'jam_selesai_istirahat.lt' => 'Jam selesai istirahat harus kurang dari jam selesai operasional.',
        ];

        $validated = $request->validate([
            'nama' => 'required|string|max:20|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|integer|min:0|max:23',
            'jam_selesai' => 'required|integer|min:0|max:23|gt:jam_mulai',
            'jam_mulai_istirahat' => 'nullable|integer|min:0|max:23|required_with:jam_selesai_istirahat|gt:jam_mulai|lt:jam_selesai',
            'jam_selesai_istirahat' => 'nullable|integer|min:0|max:23|required_with:jam_mulai_istirahat|gt:jam_mulai_istirahat|lt:jam_selesai',
        ], $messages);

        $hari->update($validated);
        return redirect()->route('hari.index')->with('success', 'Data Hari berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        Hari::findOrFail($id)->delete();
        return redirect()->route('hari.index')->with('success', 'Data Hari berhasil dihapus');
    }
}
