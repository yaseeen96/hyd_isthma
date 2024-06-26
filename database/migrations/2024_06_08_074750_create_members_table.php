<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            // $table->string("phone")->unique(); // brefore it was unite removed the unique from the phone
            $table->string("phone");
            $table->string("user_number")->nullable();
            $table->string("unit_name")->nullable();
            $table->string("zone_name")->nullable();
            $table->string("division_name")->nullable();
            $table->string("dob")->nullable();
            $table->string('gender')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};