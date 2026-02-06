<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    // app/Models/Attendance.php
    protected $fillable = [
        'event_id', 
        'user_id', 
        'status', 
        ];

    public function participant()
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
