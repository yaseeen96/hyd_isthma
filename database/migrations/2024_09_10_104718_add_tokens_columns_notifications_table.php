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
        Schema::table('notifications', function (Blueprint $table) {
            $table->json('valid_tokens')->nullable();
            $table->json('unknown_tokens')->nullable();
            $table->json('invalid_tokens')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('valid_tokens');
            $table->dropColumn('unknown_tokens');
            $table->dropColumn('invalid_tokens');
        });
    }
};