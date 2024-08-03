<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegFamilyDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'type',
        'name',
        'age',
        'gender',
        'fees',
        'interested_in_volunteering'
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

}
