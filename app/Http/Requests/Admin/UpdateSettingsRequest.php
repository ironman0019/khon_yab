<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
            'site_name' => ['sometimes', 'required', 'string', 'max:255'],
            'site_logo' => ['sometimes', 'nullable', 'image', 'max:2048'],
            'default_language_code' => ['sometimes', 'nullable', 'string', 'exists:languages,code'],
            'site_email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'site_phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'site_address' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
