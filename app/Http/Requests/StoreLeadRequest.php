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
            // Strict shape: digits, spaces, + and - only
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\s]+$/'], 
            'vehicle_info' => 'required|string|max:255',
            'service_needed' => 'required|string|max:255',
        ];
    }
}