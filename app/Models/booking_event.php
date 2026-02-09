<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class booking_event extends Model
{
    protected $fillable = [
        'booking_id',
        'from_status',
        'to_status',
        'changed_by',
        'note',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
