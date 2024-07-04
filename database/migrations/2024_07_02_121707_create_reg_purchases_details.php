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
        Schema::create('reg_purchases_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained();
            $table->string("type")->nullable()->comment("bed/cot/plate/spoons/carpet");
            $table->decimal("qty")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reg_purchases_details');
    }
};