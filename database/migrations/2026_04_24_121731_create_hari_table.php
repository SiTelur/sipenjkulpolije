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
        Schema::create('hari', function (Blueprint $table) {
            $table->id();
            $table->text('nama');
            $table->smallInteger('jam_mulai');
            $table->smallInteger('jam_selesai');
            $table->smallInteger('jam_mulai_istirahat')->nullable();
            $table->smallInteger('jam_selesai_istirahat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hari');
    }
};
