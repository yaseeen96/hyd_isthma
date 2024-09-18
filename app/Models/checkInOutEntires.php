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
}