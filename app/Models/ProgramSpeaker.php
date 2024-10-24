<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class ProgramSpeaker extends Model
{
    use HasFactory, Mediable;

    protected $fillable = ['name', 'bio', 'english_name', 'english_bio', 'malyalam_name', 'malyalam_bio', 'bengali_name', 'bengali_bio', 'tamil_name', 'tamil_bio', 'kannada_name', 'kannada_bio'];

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
