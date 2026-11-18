<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFollowUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Date lazmi hai aur aaj ya aane wale dinon ki honi chahiye
            'follow_up_date' => 'required|date|after_or_equal:today',
            'follow_up_notes' => 'nullable|string|max:500',
        ];
    }
}