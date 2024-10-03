<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic',
        'date',
        'from_time',
        'to_time',
        'program_speaker_id',
        'session_theme_id',
        'status',
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