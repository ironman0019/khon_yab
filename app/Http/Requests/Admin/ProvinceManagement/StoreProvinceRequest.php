<?php

namespace App\Http\Requests\Admin\ProvinceManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreProvinceRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:provinces,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('admin.The province name field is required.'),
            'name.unique' => __('admin.The province name has already been taken.'),
        ];
    }
}
