<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSettingsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'language_code' => ['sometimes', 'nullable', 'string', 'exists:languages,code'],
            'notifications_email' => ['sometimes', 'nullable', 'boolean'],
            'notifications_sms' => ['sometimes', 'nullable', 'boolean'],
        ];
    }
}
