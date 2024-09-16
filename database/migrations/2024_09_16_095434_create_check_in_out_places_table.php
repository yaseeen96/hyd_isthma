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
        Schema::create('check_in_out_places', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'place_name');
            $table->json('member_types')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->json('gender')->nullable();
            $table->json('zone_names')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_in_out_places');
    }
};