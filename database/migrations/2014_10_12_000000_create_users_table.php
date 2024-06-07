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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string("phone")->unique();
            $table->string("rukn_id")->nullable();
            $table->string("full_name")->nullable();
            $table->string("unit_name")->nullable();
            $table->string("district")->nullable();
            $table->string("halqa")->nullable();
            $table->string("gender")->nullable();
            $table->string("age")->nullable();
            $table->boolean("confirm_arrival")->nullable();
            $table->string("reason_for_not_coming")->nullable();
            $table->boolean("ameer_permission_taken")->nullable();
            $table->string("emergency_contact")->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};