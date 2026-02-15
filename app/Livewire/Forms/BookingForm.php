<?php

namespace App\Livewire\Forms;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\BookingPhoto;
use App\Models\DamageType;
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
    public $service_ids = [];

    #[Validate('required', message: 'Jenis kerusakan wajib dipilih')]
    public $damage_type_ids = [];

    #[Validate('nullable')]
    #[Validate('image', message: 'File harus berformat gambar (.jpg, .png, dll)')]
    #[Validate('max:1024', message: 'File harus berukuran maksimal 1 mb')]
    public $photo;

    public function store(): Booking
    {
        $this->validate();

        $booking = Booking::create([
            'user_id' => $this->user_id,
            'technician_id' => $this->technician_id,
            'booking_date' => $this->booking_date,
            'address' => $this->address,
            'notes' => $this->notes,
        ]);

        $damage_types = DamageType::whereIn('service_id', $this->service_ids)->get();

        foreach ($damage_types as $damage_type) {
            BookingItem::create([
                'booking_id' => $booking->id,
                'damage_type_id' => $damage_type->id,
            ]);
        }

        if ($this->photo) {
            $file_name = $this->photo->getClientOriginalName();
            $path = $this->photo->storeAs(path: 'booking', name: $file_name);

            BookingPhoto::create([
                'booking_id' => $booking->id,
                'path' => $path,
                'original_name' => $file_name,
            ]);
        }

        return $booking;
    }
}
