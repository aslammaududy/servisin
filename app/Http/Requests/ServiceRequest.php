<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required'],
            'description' => ['required'],
            'is_active' => ['boolean'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
