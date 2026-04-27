<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                "id" => 1,
                "created_at" => "2026-01-30 02:13:11",
                "updated_at" => "2026-01-30 02:13:11",
                "nama" => "Aula",
                "kegunaan_ruangan" => json_encode(["TEORI", "PRAKTIK"])
            ],
            [
                "id" => 2,
                "created_at" => "2026-03-05 10:15:09",
                "updated_at" => "2026-03-05 10:15:09",
                "nama" => "Ruang 101",
                "kegunaan_ruangan" => json_encode(["TEORI", "PRAKTIK"])
            ],
            [
                "id" => 3,
                "created_at" => "2026-03-05 10:15:40",
                "updated_at" => "2026-03-05 10:15:40",
                "nama" => "Lab RSI",
                "kegunaan_ruangan" => json_encode(["PRAKTIK"])
            ],
            [
                "id" => 4,
                "created_at" => "2026-03-05 10:15:58",
                "updated_at" => "2026-03-05 10:15:58",
                "nama" => "Lab SKK",
                "kegunaan_ruangan" => json_encode(["PRAKTIK"])
            ]
        ];

        Ruangan::insert($data);
    }
}
