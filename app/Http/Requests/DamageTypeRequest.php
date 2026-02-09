<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DamageTypeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'service_id' => ['required', 'exists:services'],
            'name' => ['required'],
            'description' => ['required'],
            'price' => ['required', 'integer'],
            'is_active' => ['boolean'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
