<?php

namespace App\Models;

use App\Observers\BookingObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([BookingObserver::class])]
class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'technician_id',
        'status',
        'booking_date',
        'address',
        'notes',
        'shipping_fee',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id')->where('role', 'technician');
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class, 'booking_id');
    }

    public function bookingEvents(): HasMany
    {
        return $this->hasMany(BookingEvent::class, 'booking_id');
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'booking_id');
    }

    public function paymentProofs(): HasMany
    {
        return $this->hasMany(BookingPaymentProof::class, 'booking_id');
    }

    protected function casts()
    {
        return [
            'booking_date' => 'timestamp',
        ];
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => match ($value) {
                'pending' => 'Menunggu',
                'assigned' => 'Teknisi Ditugaskan',
                'on_progress' => 'Sedang Dikerjakan',
                'done' => 'Selesai',
                'cancelled' => 'Batal'
            },
            set: fn ($value) => match ($value) {
                'Menunggu' => 'pending',
                'Teknisi Ditugaskan' => 'assigned',
                'Sedang Dikerjakan' => 'on_progress',
                'Selesai' => 'done',
                'Dibatalkan' => 'cancelled',
            }
        );
    }
}
