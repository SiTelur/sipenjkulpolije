<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("DROP TYPE IF EXISTS tipe_penggunaan CASCADE");
        DB::statement("CREATE TYPE tipe_penggunaan AS ENUM ('TEORI', 'PRAKTIK')");
        
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();
            $table->text('nama');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE ruangan ADD COLUMN kegunaan_ruangan tipe_penggunaan[]');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangan');
        DB::statement("DROP TYPE IF EXISTS tipe_penggunaan");
    }
};
