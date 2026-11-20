<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
{
    // Ye method default false hota hai, isko true karein
    public function authorize(): bool
    {
        return true; // <-- Yahan false ko true kar dein
    }

    public function rules(): array
    {
        return [
            'lead_id' => 'required|exists:leads,id',
            'to_email' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'template_id' => 'nullable|exists:email_templates,id',
        ];
    }
}