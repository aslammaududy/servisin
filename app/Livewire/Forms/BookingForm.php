<?php

namespace App\Livewire\Forms;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\BookingPhoto;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BookingForm extends Form
{
    #[Validate('nullable')]
    public $user_id = null;

    #[Validate('nullable')]
    public $technician_id = null;

    #[Validate('nullable')]
    public $status = '';

    #[Validate(['required', 'date'], message: ['required' => 'Jadwal kunjungan wajib dipilih'])]
    public $booking_date = '';

    #[Validate('required', message: 'Alamat wajib dipilih')]
    public $address = '';

    #[Validate(['nullable'])]
    public $notes = '';

    #[Validate('required', message: 'Jenis layanan wajib dipilih')]
    public  $service_id = null;

    #[Validate('required', message: 'Jenis kerusakan wajib dipilih')]
    public  $damage_type_id = null;

    #[Validate('nullable')]
    #[Validate('image', message: 'File harus berformat gambar (.jpg, .png, dll)')]
    #[Validate('max:1024', message: 'File harus berukuran maksimal 1 mb')]
    public $photo;

    public function store(): void
    {
        $this->validate();

        $booking = Booking::create([
            'user_id' => $this->user_id,
            'technician_id' => $this->technician_id,
            'booking_date' => $this->booking_date,
            'address' => $this->address,
            'notes' => $this->notes,
        ]);

        BookingItem::create([
            'booking_id' => $booking->id,
            'service_id' => $this->service_id,
            'damage_type_id' => $this->damage_type_id,
            'price' => 200000,
            'quantity' => 1,
        ]);

        if ($this->photo) {
            $file_name = $this->photo->getClientOriginalName();
            $path = $this->photo->storeAs(path: 'booking', name: $file_name);

            BookingPhoto::create([
                'booking_id' => $booking->id,
                'path' => $path,
                'original_name' => $file_name,
            ]);
        }
    }
}
