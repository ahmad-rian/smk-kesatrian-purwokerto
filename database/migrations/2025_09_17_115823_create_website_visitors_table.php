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
        Schema::create('website_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45); // IPv6 compatible
            $table->date('visit_date');
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->unique(['ip_address', 'visit_date']);
            $table->index('visit_date');
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_visitors');
    }
};
