<?php

use App\Models\Booking;
use App\Models\BookingItem;
use Livewire\Component;

new class extends Component {
    use \App\Livewire\Concerns\HasToast;

    public Booking $booking;
    public string $search_technician = '';
    public string $booking_status = '';
    public ?int $technician_id;
    public mixed $shipping_fee;
    public array $statuses = [
        'Menunggu',
        'Teknisi Ditugaskan',
        'Sedang Dikerjakan',
        'Selesai',
        'Batal'
    ];

    public function mount(Booking $booking): void
    {
        $this->booking = $booking->load(['bookingItems.damageType.service', 'user', 'technician', 'bookingEvents.changedBy']);
        $this->booking_status = $booking->status;
        $this->technician_id = $booking->technician_id;
        $this->shipping_fee = $booking->shipping_fee;
    }

    #[\Livewire\Attributes\Computed]
    public function estimatedTotal(): int
    {
        $estimated_total = $this->booking->bookingItems->reduce(function (?int $carry, \App\Models\BookingItem $item) {
            return $carry + $item->damageType->price;
        }, 0);

        return $estimated_total + $this->booking->shipping_fee;
    }

    #[\Livewire\Attributes\Computed]
    public function services(): string
    {
        $services = [];
        $this->booking->bookingItems->each(function (BookingItem $bookingItem) use (&$services) {
            $services[] = $bookingItem->damageType->service->name;
        });
        return implode(', ', array_unique($services));
    }

    #[\Livewire\Attributes\Computed]
    public function damages(): string
    {
        $damages = [];
        $this->booking->bookingItems->each(function (BookingItem $bookingItem) use (&$damages) {
            $damages[] = $bookingItem->damageType->name;
        });

        return implode(', ', array_unique($damages));;
    }

    #[\Livewire\Attributes\Computed]
    public function technicians(): Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\User::where('role', 'technician')->whereLike('name', "%$this->search_technician%")->get();
    }

    public function updatedBookingStatus(string $status): void
    {
        $this->booking->status = $status;
        $this->booking->save();

        $this->toastSuccess('Berhasil update status');
    }

    public function updatedTechnicianId(?int $id): void
    {
        $this->booking->technician_id = $id;
        $this->booking->save();

        $this->toastSuccess('Berhasil menugaskan teknisi');
    }

    public function setShippingFee(): void
    {
        $this->booking->shipping_fee = (int)str_replace(",", "", $this->shipping_fee);
        $this->booking->save();

        $this->toastSuccess('Berhasil update ongkos kirim');

    }
};
