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
            $table->string('english_topic')->nullable();
            $table->longText('english_transcript')->nullable();
            $table->string('malyalam_topic')->nullable();
            $table->longText('malyalam_transcript')->nullable();
            $table->string('bengali_topic')->nullable();
            $table->longText('bengali_transcript')->nullable();
            $table->string('tamil_topic')->nullable();
            $table->longText('tamil_transcript')->nullable();
            $table->string('kannada_topic')->nullable();
            $table->longText('kannada_transcript')->nullable();
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