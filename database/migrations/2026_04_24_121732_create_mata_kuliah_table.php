<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->text('kode')->nullable();
            $table->text('nama')->nullable();
            $table->smallInteger('semester')->nullable();
            $table->unsignedBigInteger('id_pengampu')->nullable();
            $table->smallInteger('sks_teori')->nullable();
            $table->smallInteger('sks_praktek');
            $table->boolean('is_active');
            $table->text('kelas');
            $table->timestamps();

            $table->foreign('id_pengampu')->references('id')->on('dosen')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
