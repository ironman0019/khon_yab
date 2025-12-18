<?php

namespace App\Http\Requests\Admin\ProvinceManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreCityRequest extends FormRequest
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
            'province_id' => ['required', 'integer', 'exists:provinces,id'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'province_id.required' => __('admin.The province field is required.'),
            'province_id.exists' => __('admin.The selected province does not exist.'),
            'name.required' => __('admin.The city name field is required.'),
        ];
    }
}
