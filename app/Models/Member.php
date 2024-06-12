<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Member extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        "phone",
        "user_number",
        "unit_name",
        "zone_name",
        "division_name",
        "dob",
        "gender",
        "status",
    ];

    public function registration()
    {
        return $this->HasOne(Registration::class, 'member_id', 'id');
    }
}