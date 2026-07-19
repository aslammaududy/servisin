<?php

use App\Models\Booking;
use App\Services\BookingExportService;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

new class extends Component
{
    use \Livewire\WithPagination;

    public string $dateFrom = '';

    public string $dateTo = '';

    #[\Livewire\Attributes\Computed]
    public function bookings()
    {
        return Booking::with(['user', 'technician', 'bookingItems.damageType.service'])
            ->has('bookingItems')
            ->when(auth()->user()->role === 'user', function (Builder $query) {
                return $query->where('user_id', auth()->user()->id);
            })
            ->dateFrom($this->dateFrom ?: null)
            ->dateTo($this->dateTo ?: null)
            ->latest()
            ->paginate(10);
    }

    public function resetFilters(): void
    {
        $this->dateFrom = '';
        $this->dateTo = '';
    }

    public function exportCsv()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $service = new BookingExportService;

        return $service->toCsv(
            $this->dateFrom ?: null,
            $this->dateTo ?: null,
            null,
            auth()->user()
        );
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
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
        $total = $this->bookings->total();

        return $total > 0 ? $this->completedBookings / $total : 0;
    }

    #[\Livewire\Attributes\Computed]
    public function bookingCompletionRate(): string
    {
        $rate = $this->averageCompletedBooking * 100;

        return $rate.'%';
    }

    #[\Livewire\Attributes\Computed]
    public function services(): array
    {
        $services = [];

        $this->bookings->each(function (Booking $booking) use (&$services) {
            $booking->bookingItems->each(function (\App\Models\BookingItem $bookingItem) use ($booking, &$services) {
                $services[$booking->id][] = $bookingItem->damageType->service->name;
            });
        });

        return $services;
    }

    #[\Livewire\Attributes\Computed]
    public function estimatedTotal(int $booking_id): int
    {
        $booking = $this->bookings->firstWhere('id', $booking_id);

        $estimated_total = $booking->bookingItems->reduce(function (?int $carry, \App\Models\BookingItem $item) {
            return $carry + $item->damageType->price;
        }, 0);

        return $estimated_total + ($booking->shipping_fee ?? 0);
    }
};
