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

        // MySQL AUTO_INCREMENT otomatis melanjutkan dari ID tertinggi, tidak perlu reset sequence seperti PostgreSQL
    }
}
