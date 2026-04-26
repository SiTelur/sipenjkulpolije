<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin Polije',
            'email' => 'adminpolije@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $this->call([
            DosenSeeder::class,
            HariSeeder::class,
            MataKuliahSeeder::class,
            RuanganSeeder::class,
            TeknisiSeeder::class,
        ]);

        // Fix PostgreSQL sequences because we inserted specific IDs in the seeders
        $tables = ['dosen', 'hari', 'mata_kuliah', 'ruangan', 'teknisi'];
        foreach ($tables as $table) {
            \Illuminate\Support\Facades\DB::statement("SELECT setval('{$table}_id_seq', (SELECT COALESCE(MAX(id), 1) FROM {$table}))");
        }
    }
}
