<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama_sekolah');
            $table->string('nama_singkat')->nullable();
            $table->text('tagline')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->string('logo')->nullable();
            $table->text('alamat');
            $table->string('telepon', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->json('media_sosial')->nullable(); // {instagram, facebook, youtube, tiktok}
            $table->longText('visi')->nullable();
            $table->longText('misi')->nullable();
            $table->string('nama_kepala_sekolah')->nullable();
            $table->string('foto_kepala_sekolah')->nullable();
            $table->year('tahun_berdiri')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
