<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'session_id'
    ];

    public function sessionTheme() {
        return $this->belongsTo(SessionTheme::class, 'session_id', 'id');
    }
    public function member() {
        return $this->belongsTo(Member::class);
    }
}