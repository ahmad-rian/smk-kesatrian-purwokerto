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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('ringkasan')->nullable();
            $table->longText('konten');
            $table->string('gambar')->nullable();
            $table->string('kategori')->default('Umum');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('tanggal_publikasi')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('featured')->default(false);
            $table->timestamps();
            
            // Indexes untuk performa
            $table->index(['status', 'tanggal_publikasi']);
            $table->index('kategori');
            $table->index('featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
