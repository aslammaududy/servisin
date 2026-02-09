<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplainPhotoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'complaint_id' => ['required', 'exists:complaints'],
            'path' => ['required'],
            'original_name' => ['required'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
