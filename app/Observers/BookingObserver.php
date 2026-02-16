<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\BookingEvent;

class BookingObserver
{
    public function created(Booking $booking): void
    {
        BookingEvent::create([
            'booking_id' => $booking->id,
            'status' => 'created',
            'changed_by' => auth()->id(),
        ]);
    }

    public function updated(Booking $booking): void
    {
        BookingEvent::create([
            'booking_id' => $booking->id,
            'status' => $booking->status,
            'changed_by' => auth()->id(),
        ]);
    }

    public function deleted(Booking $booking): void
    {
    }

    public function restored(Booking $booking): void
    {
    }
}
