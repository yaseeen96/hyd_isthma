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
        Schema::create('program_speakers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('bio');
            $table->string('english_name')->nullable();
            $table->longText('english_bio')->nullable();
            $table->string('malyalam_name')->nullable();
            $table->longText('malyalam_bio')->nullable();
            $table->string('bengali_name')->nullable();
            $table->longText('bengali_bio')->nullable();
            $table->string('tamil_name')->nullable();
            $table->longText('tamil_bio')->nullable();
            $table->string('kannada_name')->nullable();
            $table->longText('kannada_bio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_speakers');
    }
};