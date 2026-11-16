<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Yahan hum phone number ko normalize kar rahe hain
        protected function prepareForValidation()
    {
        if ($this->has('phone')) {
            // Strip everything except digits and +
            $phone = preg_replace('/[^\d+]/', '', $this->phone);

            // Only convert if it starts with '0' (assumed to be local Pakistani format)
            if (str_starts_with($phone, '0')) {
                $phone = '+92' . substr($phone, 1);
            } 
            // If it starts with '+', leave it as is (foreign number)
            // If it's just 10 digits starting with 3, we can optionally assume it's local
            elseif (str_starts_with($phone, '3') && strlen($phone) == 10) {
                $phone = '+92' . $phone;
            }

            $this->merge(['phone' => $phone]);
        }
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            // Ab regex update ho gaya hai kyunke number +92... format mein aayega
            'phone' => ['required', 'string', 'regex:/^\+\d{10,15}$/'], 
            'make' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1900|max:2099',
            'license_plate' => 'required|string|max:20',
            'service_needed' => 'required|string|max:255',
        ];
    }
}