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
        Schema::create('qr_batch_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->unique();
            $table->string('batch_type');
            $table->string('full_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('zone_name')->nullable();
            $table->string('division_name')->nullable();
            $table->string('unit_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_batch_registrations');
    }
};