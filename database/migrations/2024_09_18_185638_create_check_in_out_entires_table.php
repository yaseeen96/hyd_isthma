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
        Schema::create('check_in_out_entires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('batch_id');
            $table->string('batch_type');
            $table->string('gender')->nullable();
            $table->date('date');
            $table->time('time');
            $table->integer('place_id');
            $table->string('mode')->nullable();
            $table->string('name')->nullable();
            $table->string('zone_name')->nullable();
            $table->string('division_name')->nullable();
            $table->string('unit_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->foreignId('operator_id')->constrained('members');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_in_out_entires');
    }
};
