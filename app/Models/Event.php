<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QrCode;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'pax',
        'password_hash',
        'media_path',
        'location',
        'is_active',
        'is_qr_active',
        'pengurus_id',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'is_qr_active' => 'boolean',
    ];

    /**
     * =========================
     * RELATIONSHIPS
     * =========================
     */

    // Pengurus event
    public function pengurusMajlis()
    {
        return $this->belongsTo(PengurusMajlis::class, 'pengurus_id');
    }

    // Peserta event
    public function participants()
    {
        return $this->hasMany(EventParticipant::class, 'event_id');
    }

    // QR Code kehadiran (PALING PENTING)
    public function attendanceQrs()
    {
        return $this->hasMany(QrCode::class, 'event_id');
    }
}
