<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrBatchRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'batch_type',
        'full_name',
        'gender',
        'email',
        'phone_number',
        'zone_name',
        'division_name',
        'unit_name'
    ];
}