<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["id" => 1, "created_at" => "2025-07-02 12:21:05", "nama" => "Rani Purbaningtyas, S.Kom., MT.", "nidn" => "0012038203", "is_active" => true, "tipe_dosen" => "TETAP"],
            ["id" => 2, "created_at" => "2025-07-02 12:21:17", "nama" => "Adi Sucipto, S.ST., M.Tr.T.", "nidn" => "0024089501", "is_active" => false, "tipe_dosen" => "TETAP"],
            ["id" => 3, "created_at" => "2025-07-02 12:21:31", "nama" => "Sholihah Ayu Wulandari, S.ST., M.Tr.T.", "nidn" => "0024119301", "is_active" => true, "tipe_dosen" => "TETAP"],
            ["id" => 4, "created_at" => "2025-07-02 12:21:40", "nama" => "Dhony Manggala Putra, S.E., M.M.", "nidn" => "0007039207", "is_active" => true, "tipe_dosen" => "TETAP"],
            ["id" => 5, "created_at" => "2025-07-02 12:21:47", "nama" => "Rifqi Aji Widarso, S.T. M.T.", "nidn" => "8539768669130323", "is_active" => true, "tipe_dosen" => "TETAP"],
            ["id" => 6, "created_at" => "2025-07-02 12:55:14", "nama" => "Drs. Asmunir, M.M PD.", "nidn" => "123122342392734", "is_active" => true, "tipe_dosen" => "TETAP"],
            ["id" => 7, "created_at" => "2025-07-02 12:56:54", "nama" => "Iin Widayani, M.Pd", "nidn" => "12312345548657567", "is_active" => true, "tipe_dosen" => "LUAR_BIASA"],
            ["id" => 8, "created_at" => "2025-07-02 12:57:28", "nama" => "Agung Sutrisno, S.S, M.Hum", "nidn" => "98899879878979", "is_active" => true, "tipe_dosen" => "LUAR_BIASA"],
            ["id" => 10, "created_at" => "2026-01-19 23:21:54", "nama" => "Akas Bagus Setiawan S.Kom., M.MT", "nidn" => "30547696701303131", "is_active" => true, "tipe_dosen" => "TETAP"],
            ["id" => 16, "created_at" => "2026-02-03 05:33:15", "nama" => "Aris Kuswanto, ST, M.PdI ", "nidn" => "23342312313", "is_active" => true, "tipe_dosen" => "LUAR_BIASA"],
        ];

        foreach ($data as $item) {
            Dosen::updateOrInsert(
                ['id' => $item['id']],
                [
                    'nama' => $item['nama'],
                    'nidn' => $item['nidn'],
                    'is_active' => $item['is_active'],
                    'tipe_dosen' => $item['tipe_dosen'],
                    'created_at' => $item['created_at'],
                    'updated_at' => now(),
                ]
            );
        }
    }
}
