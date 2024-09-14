<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioProcessing extends Model
{
    use HasFactory;

    protected $fillable = ['youtube_url', 'language_1', 'language_2', 'directory_prefix'];
}