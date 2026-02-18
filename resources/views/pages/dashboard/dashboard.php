<?php

use App\Models\Booking;
use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination;

    #[\Livewire\Attributes\Computed]
    public function bookingItems()
    {
        return \App\Models\BookingItem::with(['booking.user', 'booking.technician', 'damageType.service'])
            ->has('booking')
            ->groupBy('booking_id')
            ->paginate(10);
    }

    #[\Livewire\Attributes\Computed]
    public function activeBookings(): int
    {
        return Booking::whereNotIn('status', ['done', 'cancelled'])->count();
    }

    #[\Livewire\Attributes\Computed]
    public function completedBookings(): int
    {
        return Booking::where('status', 'done')->count();
    }

    #[\Livewire\Attributes\Computed]
    public function averageCompletedBooking(): float
    {
        return $this->completedBookings / $this->bookingItems->total();
    }

    #[\Livewire\Attributes\Computed]
    public function bookingCompletionRate(): string
    {
        $rate = $this->averageCompletedBooking * 100;

        return $rate . '%';
    }

    #[\Livewire\Attributes\Computed]
    public function services(): array
    {
        $booking_item_ids = $this->bookingItems->pluck('booking_id');
        $services = [];

        \App\Models\Booking::with('bookingItems.damageType.service')
            ->whereIn('id', $booking_item_ids)
            ->get()
            ->each(function (Booking $booking) use (&$services) {
                return $booking->bookingItems->each(function (\App\Models\BookingItem $bookingItem) use ($booking, &$services) {
                    $services[$booking->id][] = $bookingItem->damageType->service->name;
                });
            });

        return $services;
    }
};

