<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingItemRequest extends FormRequest
{
    public function rules()
    {
        return [
            'booking_id' => ['required', 'exists:bookings'],
            'service_id' => ['required', 'exists:services'],
            'damage_type_id' => ['required', 'exists:damage_types'],
            'price' => ['required', 'integer'],
            'quantity' => ['required', 'integer'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
