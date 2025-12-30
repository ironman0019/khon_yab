<?php

namespace App\Http\Requests\Admin\ReceiverManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReceiverRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'mobile_number' => ['required', 'string', 'max:20'],
            'national_code' => ['required', 'string', 'max:20', 'unique:receivers,national_code'],
            'age' => ['required', 'integer', 'min:18', 'max:100'],
            'gender' => ['required', 'string', Rule::in(['male', 'female', 'other'])],
            'province_id' => ['required', 'integer', 'exists:provinces,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'address' => ['required', 'string'],
            'blood_type' => ['required', 'string', Rule::in(['A', 'B', 'AB', 'O'])],
            'rh_factor' => ['required', 'string', Rule::in(['positive', 'negative'])],
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
            'full_name.required' => __('admin.The full name field is required.'),
            'email.required' => __('admin.The email field is required.'),
            'email.unique' => __('admin.The email has already been taken.'),
            'password.required' => __('admin.The password field is required.'),
            'password.min' => __('admin.The password must be at least 8 characters.'),
            'password.confirmed' => __('admin.The password confirmation does not match.'),
            'mobile_number.required' => __('admin.The mobile number field is required.'),
            'national_code.required' => __('admin.The national code field is required.'),
            'national_code.unique' => __('admin.The national code has already been taken.'),
            'age.required' => __('admin.The age field is required.'),
            'province_id.required' => __('admin.The province field is required.'),
            'city_id.required' => __('admin.The city field is required.'),
            'blood_type.required' => __('admin.The blood type field is required.'),
            'rh_factor.required' => __('admin.The RH factor field is required.'),
        ];
    }
}
