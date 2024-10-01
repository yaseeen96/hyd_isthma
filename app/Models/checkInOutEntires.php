<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class checkInOutEntires extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'batch_type',
        'gender',
        'date',
        'time',
        'place_id',
        'mode',
        'name',
        'zone_name',
        'division_name',
        'unit_name',
        'phone_number',
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
