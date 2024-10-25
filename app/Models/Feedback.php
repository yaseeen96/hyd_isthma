<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedbacks';
    protected $fillable = [
        'member_id',
        'date',
        'datetime',
        'title',
        'description',
        'feedback_type',
        'program_id'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}