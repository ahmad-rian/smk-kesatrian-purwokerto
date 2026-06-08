<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_program_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#3b82f6');
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_program_categories');
    }
};
