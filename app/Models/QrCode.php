<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $table = 'qr_codes';
    protected $primaryKey = 'qr_id';

    protected $fillable = [
        'event_id',
        'user_id',
        'qr_token',
        'qr_data',
        'is_used',
        'generated_at',
        'used_at',
    ];
    
    protected $casts = [
        'qr_data' => 'array',
        'is_used' => 'boolean',
        'generated_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
