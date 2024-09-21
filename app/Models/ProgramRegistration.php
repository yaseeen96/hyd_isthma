<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'program_id'
    ];

    public function program() {
        return $this->belongsTo(Program::class);
    }
    public function member() {
        return $this->belongsTo(Member::class);
    }
}