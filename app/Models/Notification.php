<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class Notification extends Model
{
    use HasFactory, Mediable;

    protected $fillable = ['title', 'message', 'criteria', 'youtube_url'];

    protected $casts = [
        'criteria' => 'array'
    ];
}
