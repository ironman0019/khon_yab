<?php

namespace App\Http\Requests\Admin\LanguageManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLanguageRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:10', 'unique:languages,code'],
            'name' => ['required', 'string', 'max:255'],
            'native_name' => ['required', 'string', 'max:255'],
            'direction' => ['required', 'string', Rule::in(['ltr', 'rtl'])],
            'is_active' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => __('admin.The code field is required.'),
            'code.unique' => __('admin.The code has already been taken.'),
            'name.required' => __('admin.The name field is required.'),
            'native_name.required' => __('admin.The native name field is required.'),
            'direction.required' => __('admin.The direction field is required.'),
            'direction.in' => __('admin.The selected direction is invalid.'),
        ];
    }
}
