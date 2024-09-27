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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('topic');
            $table->datetime('date');
            $table->time('from_time');
            $table->time('to_time');
            $table->foreignId('program_speaker_id')->constrained('program_speakers');
            $table->foreignId('session_theme_id')->constrained('session_themes');
            $table->string('status')->default('Yet to Start');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
