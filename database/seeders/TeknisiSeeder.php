<?php

namespace Database\Seeders;

use App\Models\Teknisi;
use Illuminate\Database\Seeder;

class TeknisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["id" => 1, "created_at" => "2026-04-15 00:38:27", "nama" => "Zed", "is_active" => true],
            ["id" => 2, "created_at" => "2026-04-15 00:38:34", "nama" => "Saiful", "is_active" => true],
            ["id" => 3, "created_at" => "2026-04-15 00:38:48", "nama" => "Deni", "is_active" => true],
            ["id" => 4, "created_at" => "2026-04-15 00:39:01", "nama" => "Ahmad", "is_active" => true],
        ];

        foreach ($data as $item) {
            Teknisi::updateOrCreate(
                ['id' => $item['id']],
                [
                    'nama' => $item['nama'],
                    'is_active' => $item['is_active'],
                    'created_at' => $item['created_at'],
                    'updated_at' => now(),
                ]
            );
        }
    }
}
