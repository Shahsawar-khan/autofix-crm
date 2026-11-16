<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow request
    }

           public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\s]+$/'], 
            'make' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1900|max:2099',
            'license_plate' => 'required|string|max:20',
            'service_needed' => 'required|string|max:255',
        ];
    }
}