<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('meta_description')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('show_hero')->default(true);
            $table->string('hero_style')->default('gradient'); // gradient, image, simple
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        // Add custom_page_id to frontend_menus
        Schema::table('frontend_menus', function (Blueprint $table) {
            $table->foreignId('custom_page_id')->nullable()->after('css_class')
                ->constrained('custom_pages')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('frontend_menus', function (Blueprint $table) {
            $table->dropForeign(['custom_page_id']);
            $table->dropColumn('custom_page_id');
        });

        Schema::dropIfExists('custom_pages');
    }
};
