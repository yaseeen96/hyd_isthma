<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class Program extends Model
{
    use HasFactory, Mediable;

    protected $fillable = [
        'topic',
        'date',
        'from_time',
        'to_time',
        'program_speaker_id',
        'session_theme_id',
        'status',
        'english_topic',
        'english_transcript',
        'malyalam_topic',
        'malyalam_transcript',
        'bengali_topic',
        'bengali_transcript',
        'tamil_topic',
        'tamil_transcript',
        'kannada_topic',
        'kannada_transcript',
    ];
    public function programSpeaker()
    {
        return $this->belongsTo(ProgramSpeaker::class);
    }
    public function sessionTheme()
    {
        return $this->belongsTo(SessionTheme::class);
    }
    public function programRegistrations()
    {
        return $this->hasMany(ProgramRegistration::class);
    }
}