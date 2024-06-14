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
        "emergency_contact"
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}