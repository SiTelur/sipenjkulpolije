<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Note: MySQL tidak support array type seperti PostgreSQL.
     * Kolom kegunaan_ruangan disimpan sebagai JSON array string ('TEORI', 'PRAKTIK').
     */
    public function up(): void
    {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();
            $table->text('nama');
            // Disimpan sebagai JSON array, misal: ["TEORI","PRAKTIK"]
            $table->json('kegunaan_ruangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};
