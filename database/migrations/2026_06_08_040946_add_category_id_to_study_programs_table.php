<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('study_programs', function (Blueprint $table) {
            $table->foreignId('study_program_category_id')->nullable()->after('urutan')
                ->constrained('study_program_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('study_programs', function (Blueprint $table) {
            $table->dropForeign(['study_program_category_id']);
            $table->dropColumn('study_program_category_id');
        });
    }
};
