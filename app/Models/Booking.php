<?php

namespace App\Models;

use App\Observers\BookingObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @use HasFactory<\Database\Factories\BookingFactory> */
#[ObservedBy([BookingObserver::class])]
class Booking extends Model
{
    use HasFactory;

    public static array $allowedTransitions = [
        'pending' => ['assigned', 'cancelled'],
        'assigned' => ['on_progress', 'cancelled'],
        'on_progress' => ['done', 'cancelled'],
        'done' => ['cancelled'],
        'cancelled' => [],
    ];

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

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::$allowedTransitions[$this->attributes['status']] ?? []);
    }

    protected function casts()
    {
        return [
            'booking_date' => 'datetime',
        ];
    }

    public function scopeDateFrom($query, ?string $date): void
    {
        if ($date) {
            $query->where('booking_date', '>=', Carbon::parse($date)->startOfDay());
        }
    }

    public function scopeDateTo($query, ?string $date): void
    {
        if ($date) {
            $query->where('booking_date', '<=', Carbon::parse($date)->endOfDay());
        }
    }

    public function scopeOfStatus($query, ?string $status): void
    {
        if ($status) {
            $query->where('status', $status);
        }
    }

    public function scopeForExport($query, ?string $dateFrom, ?string $dateTo, ?string $status): void
    {
        $query->dateFrom($dateFrom)
            ->dateTo($dateTo)
            ->ofStatus($status)
            ->with(['user', 'technician', 'bookingItems.damageType.service']);
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
                default => $value,
            }
        );
    }
}
