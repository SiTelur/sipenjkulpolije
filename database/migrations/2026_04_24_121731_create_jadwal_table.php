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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_success');
            $table->json('jadwal')->nullable();
            $table->text('semester');
            $table->json('jadwal_view')->nullable();
            $table->text('title');
            $table->smallInteger('unscheduled_count');
            $table->json('unscheduled_items');
            $table->json('summary');
            $table->json('teknisi_summary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
