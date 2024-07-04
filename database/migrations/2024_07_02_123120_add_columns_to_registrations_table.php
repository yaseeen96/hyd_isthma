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
        Schema::table('registrations', function (Blueprint $table) {
            $table->decimal("member_fees")->nullable();
            // mehram details are in different table
            $table->json('arrival_details')->nullable()
                    ->comment('
                    {"datetime": "2024-06-08", 
                    "mode": "bus/plane", 
                    "mode_identifier": "BusNo", 
                    "start_point": "Hyd", 
                    "end_point": "Mumbai"}');
            $table->json('departure_details')->nullable()
                    ->comment('
                    {"datetime": "2024-06-08", 
                    "mode": "bus/plane", 
                    "mode_identifier": "BusNo", 
                    "start_point": "Hyd", 
                    "end_point": "Mumbai"}');
            $table->string("hotel_required")->nullable();
            $table->json("special_considerations")->nullable()
                    ->comment('
                    {food_preferences: "preferences",
                    need_attendant: "yes/no",
                    cot_or_bed: "yes/no"}');
            $table->json("sight_seeing")->nullable()->comment('
                    {required: "yes/no",
                    members_count: 0}');
            $table->string("health_concern")->nullable();
            $table->string("management_experience")->nullable();
            // purchases required details are in different table 
            $table->string("comments")->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn("member_fees");
            $table->dropColumn("arrival_details");
            $table->dropColumn("departure_details");
            $table->dropColumn("hotel_required");
            $table->dropColumn("special_considerations");
            $table->dropColumn("sight_seeing");
            $table->dropColumn("health_concern");
            $table->dropColumn("management_experience");
            $table->dropColumn("comments"); 
        });
    }
};