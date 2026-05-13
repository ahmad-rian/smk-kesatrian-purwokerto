<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('default_page_layout')->default('contained')->after('deskripsi');
            $table->string('navbar_style')->default('floating')->after('default_page_layout');
            $table->boolean('footer_full_width')->default(false)->after('navbar_style');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['default_page_layout', 'navbar_style', 'footer_full_width']);
        });
    }
};
