<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionTheme extends Model
{
    use HasFactory;
    protected $fillable = [
        'theme_name',
        'english_theme_name',
        'malyalam_theme_name',
        'bengali_theme_name',
        'tamil_theme_name',
        'kannada_theme_name',
        'status',
        'theme_type',
        'convener',
        'date',
        'from_time',
        'to_time',
        'hall_name'
    ];

    public function programs() {
        return $this->hasMany(Program::class);
    }
}
