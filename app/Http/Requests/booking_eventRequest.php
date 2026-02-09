<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class booking_eventRequest extends FormRequest
{
    public function rules()
    {
        return [
            'booking_id' => ['required', 'exists:bookings'],
            'from_status' => ['required'],
            'to_status' => ['required'],
            'changed_by' => ['required', 'exists:users'],
            'note' => ['required'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
