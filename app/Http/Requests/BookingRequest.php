<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => ['required', 'exists:users'],
            'technician_id' => ['nullable', 'exists:users'],
            'status' => ['required'],
            'booking_date' => ['required', 'date'],
            'address' => ['required'],
            'notes' => ['nullable'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
