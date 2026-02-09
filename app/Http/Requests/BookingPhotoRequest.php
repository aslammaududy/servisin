<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingPhotoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'booking_id' => ['required', 'exists:bookings'],
            'type' => ['required'],
            'path' => ['required'],
            'original_name' => ['required'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
