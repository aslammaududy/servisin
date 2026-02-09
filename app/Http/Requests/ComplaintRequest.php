<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintRequest extends FormRequest
{
    public function rules()
    {
        return [
            'booking_id' => ['required', 'exists:bookings'],
            'user_id' => ['required', 'exists:users'],
            'message' => ['required'],
            'status' => ['required'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
