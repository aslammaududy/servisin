<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPhoto extends Model
{
    protected $fillable = [
        'booking_id',
        'type',
        'path',
        'original_name',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
