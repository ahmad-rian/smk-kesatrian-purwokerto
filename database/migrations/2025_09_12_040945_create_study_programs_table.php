<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_programs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('kode', 10)->unique(); // TKR, TSM, TJKT, TAV, DKV
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->string('warna', 7)->default('#3b82f6');
            $table->json('kompetensi')->nullable();
            $table->json('prospek_karir')->nullable();
            $table->string('ketua_program')->nullable();
            $table->boolean('aktif')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->index(['aktif', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_programs');
    }
};
