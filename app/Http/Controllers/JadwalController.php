<?php

namespace App\Http\Controllers;

use App\Algoritm\Hari as HariAlgoritm;
use App\Models\Dosen;
use App\Models\Hari;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Models\Teknisi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::orderBy('created_at', 'desc')->paginate(10);
        return view('jadwal.list', compact('jadwals'));
    }

    public function create(Request $request)
    {
        $jenis = $request->input('jenis', 'ganjil');

        // Just return the view with the selected semester; data is fetched via AJAX
        return view('jadwal.generate', compact('jenis'));
    }

    public function show($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        return view('jadwal.show', compact('jadwal'));
    }

    public function export($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $filename = 'Jadwal-' . str_replace(' ', '-', $jadwal->title) . '-' . date('YmdHis') . '.xlsx';
        return Excel::download(new \App\Exports\JadwalExport($jadwal), $filename);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'semester' => 'required|in:ganjil,genap',
            'durasi_praktikum' => 'nullable|numeric|min:1|max:8',
            'override_praktikum' => 'nullable|in:0,1'
        ]);

        $semesterList = $request->semester === 'ganjil' ? [1, 3, 5, 7] : [2, 4, 6, 8];


        $dbHari = Hari::all();
        $hariAlg = [];
        foreach ($dbHari as $h) {
            $hariAlg[] = new HariAlgoritm(
                $h->nama,
                $h->jam_mulai,
                $h->jam_selesai,
                $h->jam_mulai_istirahat,
                $h->jam_selesai_istirahat
            );
        }

        $dbMk = MataKuliah::where('is_active', true)
            ->whereIn('semester', $semesterList)
            ->with('pengampu')
            ->get();

        $dosenMap = [];
        $mkAlg = [];
        foreach ($dbMk as $mk) {
            if (!$mk->pengampu)
                continue;

            if (!isset($dosenMap[$mk->id_pengampu])) {
                $dosenMap[$mk->id_pengampu] = new \App\Algoritm\Dosen(
                    $mk->pengampu->id,
                    $mk->pengampu->nama
                );
            }

            $mkAlg[] = new \App\Algoritm\MataKuliah(
                $mk->kode,
                $mk->nama,
                $dosenMap[$mk->id_pengampu],
                $mk->sks_teori,
                $mk->sks_praktek,
                $mk->semester,
                $mk->kelas ?? ''
            );
        }

        $dbRuangan = Ruangan::all();
        $ruanganAlg = [];
        foreach ($dbRuangan as $r) {
            $kegunaan = is_array($r->kegunaan_ruangan) ? $r->kegunaan_ruangan : [];
            $ruanganAlg[] = new \App\Algoritm\Ruangan(
                $r->nama,
                $kegunaan
            );
        }

        $dbTeknisi = Teknisi::where('is_active', true)->get();
        $teknisiAlg = [];
        foreach ($dbTeknisi as $t) {
            $teknisiAlg[] = new \App\Algoritm\Teknisi(
                $t->id,
                $t->nama
            );
        }

        $alg = new \App\Algoritm\WelchPowellAlgorithm();

        $overrideDurasi = ($request->override_praktikum == 1) ? (int) $request->durasi_praktikum : null;

        [$isSuccess, $jadwalItems, $unscheduled] = $alg->buatJadwal(
            $mkAlg,
            $ruanganAlg,
            $hariAlg,
            $teknisiAlg,
            $overrideDurasi
        );

        $referensiRombelDB = MataKuliah::where('is_active', true)->get();
        $referensiRombelAlg = [];
        foreach ($referensiRombelDB as $r) {
            $referensiRombelAlg[] = new \App\Algoritm\MataKuliah(
                $r->kode,
                $r->nama,
                new \App\Algoritm\Dosen($r->id_pengampu ?? 0, ''), // Only need kode, semester, kelas for reference
                $r->sks_teori,
                $r->sks_praktek,
                $r->semester,
                $r->kelas ?? ''
            );
        }

        $jadwalData = $alg->jadwalToArray(
            $request->title,
            $isSuccess,
            $jadwalItems,
            $request->semester,
            $mkAlg,
            $referensiRombelAlg,
            $unscheduled
        );

        $jadwal = new Jadwal();
        $jadwal->title = $request->title;
        $jadwal->semester = $request->semester;
        $jadwal->is_success = $isSuccess;
        $jadwal->jadwal = $jadwalData['jadwal'];
        $jadwal->jadwal_view = $jadwalData['jadwal_view'];
        $jadwal->unscheduled_count = $jadwalData['unscheduled_count'];
        $jadwal->unscheduled_items = $jadwalData['unscheduled_items'];
        $jadwal->summary = $jadwalData['summary'] ?? [];
        $jadwal->teknisi_summary = $jadwalData['teknisi_summary'] ?? [];
        $jadwal->save();

        return redirect()->route('jadwal.list')->with('success', 'Jadwal berhasil digenerate.');
    }

    public function preview(Request $request, $type)
    {
        $jenis = $request->input('jenis', 'ganjil');

        $semesterList = $jenis === 'ganjil' ? [1, 3, 5, 7] : [2, 4, 6, 8];

        switch ($type) {
            case 'matakuliah':
                $query = MataKuliah::with('pengampu')->where('is_active', true);
                if ($jenis !== '') {
                    $query->whereIn('semester', $semesterList);
                }
                $data = $query->orderBy('semester')->orderBy('nama')->get()->map(function ($mk) {
                    return [
                        'kode' => $mk->kode,
                        'nama' => $mk->nama,
                        'semester' => $mk->semester,
                        'sks' => $mk->sks_teori + $mk->sks_praktek,
                        'tipe' => $mk->sks_praktek > 0 ? 'Praktikum' : 'Teori',
                        'pengampu' => $mk->pengampu ? $mk->pengampu->nama : '–',
                    ];
                });
                return response()->json(['data' => $data]);

            case 'dosen':
                $query = Dosen::where('is_active', true);
                if ($jenis !== '') {
                    $query->whereHas('mataKuliah', function ($q) use ($semesterList) {
                        $q->where('is_active', true)->whereIn('semester', $semesterList);
                    })->with([
                                'mataKuliah' => function ($q) use ($semesterList) {
                                    $q->where('is_active', true)->whereIn('semester', $semesterList);
                                }
                            ]);
                } else {
                    $query->with('mataKuliah');
                }
                $data = $query->orderBy('nama')->get()->map(function ($d) {
                    return [
                        'nama' => $d->nama,
                        'nidn' => $d->nidn ?? '–',
                        'tipe' => $d->tipe_dosen ? str_replace('_', ' ', $d->tipe_dosen->value) : '–',
                        'matkul' => $d->mataKuliah->pluck('nama')->join(', ') ?: '–'
                    ];
                });
                return response()->json(['data' => $data]);

            case 'ruangan':
                $data = Ruangan::orderBy('nama')->get()->map(function ($r) {
                    $uses = is_array($r->kegunaan_ruangan) ? $r->kegunaan_ruangan : [];
                    return [
                        'nama' => $r->nama,
                        'kegunaan' => count($uses) > 0 ? implode(', ', $uses) : '–'
                    ];
                });
                return response()->json(['data' => $data]);

            case 'hari':
                $data = Hari::all()->map(function ($h) {
                    $mulai = str_pad($h->jam_mulai, 2, '0', STR_PAD_LEFT) . ':00';
                    $selesai = str_pad($h->jam_selesai, 2, '0', STR_PAD_LEFT) . ':00';
                    $istM = $h->jam_mulai_istirahat ? str_pad($h->jam_mulai_istirahat, 2, '0', STR_PAD_LEFT) . ':00' : null;
                    $istS = $h->jam_selesai_istirahat ? str_pad($h->jam_selesai_istirahat, 2, '0', STR_PAD_LEFT) . ':00' : null;

                    return [
                        'nama' => $h->nama,
                        'waktu' => "$mulai – $selesai",
                        'istirahat' => $istM ? "$istM – $istS" : '–'
                    ];
                });
                return response()->json(['data' => $data]);

            case 'teknisi':
                $data = Teknisi::where('is_active', true)->orderBy('nama')->get()->map(function ($t) {
                    return [
                        'nama' => $t->nama,
                        'status' => 'Aktif'
                    ];
                });
                return response()->json(['data' => $data]);
        }

        return response()->json(['data' => []]);
    }
}
