<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create custom ENUM type for PostgreSQL if it doesn't exist
        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'dosen_type') THEN CREATE TYPE dosen_type AS ENUM ('TETAP', 'LUAR_BIASA'); END IF; END $$;");

        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->text('nama')->nullable();
            $table->text('nidn');
            $table->boolean('is_active');
            $table->timestamps();
        });

        // Add the column using the custom type
        DB::statement("ALTER TABLE dosen ADD COLUMN tipe_dosen dosen_type NOT NULL DEFAULT 'TETAP'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
        // We don't necessarily drop the type here to avoid issues if other migrations use it,
        // but since it's a fresh start, it's safer to handle existence in up().
    }
};
