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
            $table->bigInteger('user_id')->nullable();
            $table->string('type')->nullable();
            $table->string('rukun_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('unit')->nullable();
            $table->string('halqa')->nullable();
            $table->string('company_name')->nullable();
            $table->string('department_name')->nullable();
            $table->string('govt_organization')->nullable();
            $table->integer('place_id');
            $table->dateTime('datetime')->nullable();
            $table->string('mode')->nullable();
            $table->string('operator_id')->nullable();
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