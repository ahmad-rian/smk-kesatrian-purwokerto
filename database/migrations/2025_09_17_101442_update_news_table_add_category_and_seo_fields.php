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
        Schema::table('news', function (Blueprint $table) {
            // Add category relationship
            $table->foreignId('news_category_id')->nullable()->after('kategori')->constrained('news_categories')->onDelete('set null');

            // Add missing SEO and content fields if they don't exist
            if (!Schema::hasColumn('news', 'penulis')) {
                $table->string('penulis')->nullable()->after('tanggal_publikasi');
            }

            if (!Schema::hasColumn('news', 'tags')) {
                $table->json('tags')->nullable()->after('penulis');
            }

            if (!Schema::hasColumn('news', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('tags');
            }

            if (!Schema::hasColumn('news', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }

            if (!Schema::hasColumn('news', 'meta_keywords')) {
                $table->json('meta_keywords')->nullable()->after('meta_description');
            }

            // Add visitor tracking relationship
            $table->string('visitor_cookie')->nullable()->after('views');

            // Add indexes for performance
            $table->index('news_category_id');
            $table->index('penulis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['news_category_id']);
            $table->dropIndex(['news_category_id']);
            $table->dropIndex(['penulis']);
            $table->dropColumn([
                'news_category_id',
                'visitor_cookie'
            ]);

            // Only drop columns if they were added by this migration
            if (Schema::hasColumn('news', 'penulis')) {
                $table->dropColumn('penulis');
            }
        });
    }
};
