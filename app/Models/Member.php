<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Member extends Authenticatable
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
        "push_token",
        "age",
        "year_of_rukniyat"
    ];



    public function registration()
    {
        return $this->HasOne(Registration::class, 'member_id', 'id');
    }

    public function regFamilyDetails()
    {
        return $this->HasMany(RegFamilyDetail::class, 'registration_id', 'id');
    }
}