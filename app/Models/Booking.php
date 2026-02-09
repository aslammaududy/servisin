<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'technician_id',
        'status',
        'booking_date',
        'address',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id')->where('role', 'technician');
    }

    protected function casts()
    {
        return [
            'booking_date' => 'timestamp',
        ];
    }
}
