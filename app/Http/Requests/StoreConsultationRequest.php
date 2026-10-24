<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreConsultationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'company' => ['nullable', 'string', 'max:160'],
            'preferred_contact' => ['required', 'in:email,phone'],
            'preferred_time' => ['nullable', 'string', 'max:120'],
            'topic' => ['required', 'in:consulting,platform,partnership,research,media,general'],
            'message' => ['nullable', 'string', 'max:3000'],
            'consent' => ['accepted'],
        ];
    }
}
