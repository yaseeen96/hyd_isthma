<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class checkInOutEntires extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'rukun_id',
        'name',
        'age',
        'gender',
        'unit',
        'halqa',
        'company_name',
        'department_name',
        'govt_organization',
        'place_id',
        'datetime',
        'mode',
        'operator_id'
    ];

    public function checkInOutPlace()
    {
        return $this->belongsTo(CheckInOutPlace::class, 'place_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'operator_id', 'id');
    }
}