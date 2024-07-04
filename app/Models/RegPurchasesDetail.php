<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegPurchasesDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'type',
        'qty'
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}