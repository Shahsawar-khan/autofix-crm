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
            // 1. Remove everything except digits and the + sign
            $phone = preg_replace('/[^\d+]/', '', $this->phone);

            // 2. Convert local formats to international (+92 for Pakistan)
            if (str_starts_with($phone, '0')) {
                $phone = '+92' . substr($phone, 1);
            } elseif (str_starts_with($phone, '92')) {
                $phone = '+' . $phone;
            } elseif (str_starts_with($phone, '3') && strlen($phone) == 10) {
                $phone = '+92' . $phone;
            }

            // 3. Merge it back into the request
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