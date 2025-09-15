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
        Schema::create('facility_images', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('facility_id')->constrained('facilities')->cascadeOnDelete();
            $table->string('gambar'); // Path ke file gambar
            $table->string('alt_text')->nullable(); // Alt text untuk accessibility
            $table->integer('urutan')->default(0); // Urutan tampil gambar
            $table->boolean('is_primary')->default(false); // Gambar utama
            $table->timestamps();

            $table->index(['facility_id', 'urutan']);
            $table->index(['facility_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_images');
    }
};
