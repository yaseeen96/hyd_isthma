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
        Schema::table('programs', function (Blueprint $table) {
            $table->string('url')->nullable();
            $table->string('english_url')->nullable();
            $table->string('malyalam_url')->nullable();
            $table->string('bengali_url')->nullable();
            $table->string('tamil_url')->nullable();
            $table->string('kannada_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('url');
            $table->dropColumn('english_url');
            $table->dropColumn('malyalam_url');
            $table->dropColumn('bengali_url');
            $table->dropColumn('tamil_url');
            $table->dropColumn('kannada_url');
        });
    }
};