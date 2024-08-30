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
        "fees_paid_to_ameer"
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
    public function familyDetails()
    {
        return $this->hasMany(RegFamilyDetail::class, 'registration_id');
    }
    public function purchaseDetails()
    {
        return $this->hasMany(RegPurchasesDetail::class, 'registration_id');
    }
    public function scopeConfirmArrival($query, $value) {
        if ($value == 1 || $value == 0)
           $query->where('confirm_arrival', $value);

        return $query;
    }
}