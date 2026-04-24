<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'message',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function complainPhotos(): HasMany
    {
        return $this->hasMany(ComplainPhoto::class, 'complaint_id');
    }
}
