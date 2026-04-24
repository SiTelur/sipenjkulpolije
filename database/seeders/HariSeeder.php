<?php

namespace Database\Seeders;

use App\Models\Hari;
use Illuminate\Database\Seeder;

class HariSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["id" => 1, "created_at" => "2026-03-04 00:18:51", "nama" => "Senin", "jam_mulai" => 7, "jam_selesai" => 17, "jam_mulai_istirahat" => 12, "jam_selesai_istirahat" => 13],
            ["id" => 2, "created_at" => "2026-03-05 10:12:05", "nama" => "Selasa", "jam_mulai" => 7, "jam_selesai" => 17, "jam_mulai_istirahat" => 12, "jam_selesai_istirahat" => 13],
            ["id" => 3, "created_at" => "2026-03-05 10:12:38", "nama" => "Rabu", "jam_mulai" => 7, "jam_selesai" => 17, "jam_mulai_istirahat" => 12, "jam_selesai_istirahat" => 13],
            ["id" => 4, "created_at" => "2026-03-05 10:12:55", "nama" => "Kamis", "jam_mulai" => 7, "jam_selesai" => 17, "jam_mulai_istirahat" => 12, "jam_selesai_istirahat" => 13],
            ["id" => 5, "created_at" => "2026-03-05 10:13:11", "nama" => "Jumat", "jam_mulai" => 7, "jam_selesai" => 17, "jam_mulai_istirahat" => 11, "jam_selesai_istirahat" => 13],
        ];

        foreach ($data as $item) {
            Hari::updateOrCreate(
                ['id' => $item['id']],
                [
                    'nama' => $item['nama'],
                    'jam_mulai' => $item['jam_mulai'],
                    'jam_selesai' => $item['jam_selesai'],
                    'jam_mulai_istirahat' => $item['jam_mulai_istirahat'],
                    'jam_selesai_istirahat' => $item['jam_selesai_istirahat'],
                    'created_at' => $item['created_at'],
                    'updated_at' => now(),
                ]
            );
        }
    }
}
