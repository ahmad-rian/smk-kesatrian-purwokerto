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
        Schema::table('school_activities', function (Blueprint $table) {
            $table->longText('konten')->nullable()->after('deskripsi');
            $table->time('jam_mulai')->nullable()->after('tanggal_selesai');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_activities', function (Blueprint $table) {
            $table->dropColumn(['konten', 'jam_mulai', 'jam_selesai']);
        });
    }
};
