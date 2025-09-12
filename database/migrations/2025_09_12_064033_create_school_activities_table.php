<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_activities', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama_kegiatan');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('gambar_utama')->nullable();
            $table->string('kategori')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('penanggungjawab')->nullable();
            $table->boolean('aktif')->default(true);
            $table->boolean('unggulan')->default(false);
            $table->foreignUlid('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['kategori', 'aktif']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_activities');
    }
};
