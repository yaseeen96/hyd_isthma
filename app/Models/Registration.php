<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        "member_id",
        "confirm_arrival",
        "reason_for_not_coming",
        "ameer_permission_taken",
        "emergency_contact",
        "member_fees",
        "arrival_details",
        "departure_details",
        "hotel_required",
        "special_considerations",
        "sight_seeing",
        "health_concern",
        "management_experience",
        "comments",
    ];

    protected $casts = [
        "arrival_details" => "array",
        "departure_details" => "array",
        "special_considerations" => "array",
        "sight_seeing" => "array",
    ];
    
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}