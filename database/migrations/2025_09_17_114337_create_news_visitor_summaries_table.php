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
        Schema::create('news_visitor_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained('news')->onDelete('cascade');
            $table->date('visit_date');
            $table->integer('unique_visitors')->default(0);
            $table->integer('total_visits')->default(0);
            $table->json('visitor_ips')->nullable(); // Untuk tracking IP unik per hari
            $table->timestamps();

            // Index untuk performa
            $table->unique(['news_id', 'visit_date']);
            $table->index(['visit_date']);
            $table->index(['news_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_visitor_summaries');
    }
};
