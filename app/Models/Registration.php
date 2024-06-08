<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "rukn_id",
        "full_name",
        "unit_name",
        "district",
        "halqa",
        "gender",
        "confirm_arrival",
        "reason_for_not_coming",
        "ameer_permission_taken",
        "emergency_contact"
    ];
}