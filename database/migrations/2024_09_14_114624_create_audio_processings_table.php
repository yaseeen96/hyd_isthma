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
        Schema::create('audio_processings', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_url');
            $table->string('language_1');
            $table->string('language_2');
            $table->string('directory_prefix');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio_processings');
    }
};