<?php

namespace App\Livewire\Forms;

use App\Models\Booking;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BookingForm extends Form
{
    #[Validate(['required', 'integer'])]
    public $user_id = '';

    #[Validate(['nullable', 'integer'])]
    public $technician_id = '';

    #[Validate(['required'])]
    public $status = '';

    #[Validate(['required', 'date'])]
    public $booking_date = '';

    #[Validate(['required'])]
    public $address = '';

    #[Validate(['nullable'])]
    public $notes = '';

    public function store()
    {
        $this->validate();

        Booking::create($this->all());
    }
}
