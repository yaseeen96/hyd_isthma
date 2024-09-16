<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckInOutPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_name',
        'member_types',
        'min_age',
        'max_age',
        'gender',
        'zone_names'
    ];

    protected $casts = [
        'member_types' => 'array',
        'gender' => 'array',
        'zone_names' => 'array'
    ];
}