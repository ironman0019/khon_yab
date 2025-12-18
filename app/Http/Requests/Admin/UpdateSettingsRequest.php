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

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'site_name.required' => __('admin.The site name field is required.'),
            'site_name.string' => __('admin.The site name must be a string.'),
            'site_name.max' => __('admin.The site name may not be greater than :max characters.'),
            'site_logo.image' => __('admin.The site logo must be an image.'),
            'site_logo.max' => __('admin.The site logo may not be greater than :max kilobytes.'),
            'default_language_code.string' => __('admin.The default language code must be a string.'),
            'default_language_code.exists' => __('admin.The selected default language code is invalid.'),
            'site_email.email' => __('admin.The site email must be a valid email address.'),
            'site_email.max' => __('admin.The site email may not be greater than :max characters.'),
            'site_phone.string' => __('admin.The site phone must be a string.'),
            'site_phone.max' => __('admin.The site phone may not be greater than :max characters.'),
            'site_address.string' => __('admin.The site address must be a string.'),
        ];
    }
}
