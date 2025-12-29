<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreRegisteredUserRequest extends FormRequest
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
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'user_type' => ['required', 'integer', Rule::enum(UserType::class)],
        ];

        // Add donor-specific validation if user_type is donor
        if ($this->user_type == UserType::Donor->value) {
            $rules = array_merge($rules, [
                'mobile_number' => ['required', 'string', 'max:20'],
                'national_code' => ['required', 'string', 'max:20', 'unique:donors,national_code'],
                'age' => ['required', 'integer', 'min:18', 'max:100'],
                'gender' => ['required', 'string', Rule::in(['male', 'female', 'other'])],
                'province_id' => ['required', 'integer', 'exists:provinces,id'],
                'city_id' => ['required', 'integer', 'exists:cities,id'],
                'address' => ['required', 'string'],
                'blood_type' => ['required', 'string', Rule::in(['A', 'B', 'AB', 'O'])],
                'rh_factor' => ['required', 'string', Rule::in(['positive', 'negative'])],
            ]);
        }

        // Add laboratory-specific validation if user_type is laboratory
        if ($this->user_type == UserType::Laboratory->value) {
            $rules = array_merge($rules, [
                'laboratory_name' => ['required', 'string', 'max:255'],
                'laboratory_code' => ['required', 'string', 'max:50', 'unique:laboratories,laboratory_code'],
                'laboratory_mobile_number' => ['required', 'string', 'max:20'],
                'laboratory_phone_number' => ['nullable', 'string', 'max:20'],
                'laboratory_province_id' => ['required', 'integer', 'exists:provinces,id'],
                'laboratory_city_id' => ['required', 'integer', 'exists:cities,id'],
                'laboratory_address' => ['required', 'string'],
                'license_number' => ['nullable', 'string', 'max:100'],
                'contact_person_name' => ['required', 'string', 'max:255'],
            ]);
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => __('validation.required', ['attribute' => __('Full Name')]),
            'email.required' => __('validation.required', ['attribute' => __('Email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('Email')]),
            'password.required' => __('validation.required', ['attribute' => __('Password')]),
            'user_type.required' => __('validation.required', ['attribute' => __('User Type')]),
            'mobile_number.required' => __('validation.required', ['attribute' => __('Mobile Number')]),
            'national_code.required' => __('validation.required', ['attribute' => __('National Code')]),
            'national_code.unique' => __('validation.unique', ['attribute' => __('National Code')]),
            'age.required' => __('validation.required', ['attribute' => __('Age')]),
            'province_id.required' => __('validation.required', ['attribute' => __('Province')]),
            'city_id.required' => __('validation.required', ['attribute' => __('City')]),
            'blood_type.required' => __('validation.required', ['attribute' => __('Blood Type')]),
            'rh_factor.required' => __('validation.required', ['attribute' => __('RH Factor')]),
            'laboratory_name.required' => __('validation.required', ['attribute' => __('Laboratory Name')]),
            'laboratory_code.required' => __('validation.required', ['attribute' => __('Laboratory Code')]),
            'laboratory_code.unique' => __('validation.unique', ['attribute' => __('Laboratory Code')]),
            'laboratory_mobile_number.required' => __('validation.required', ['attribute' => __('Mobile Number')]),
            'laboratory_province_id.required' => __('validation.required', ['attribute' => __('Province')]),
            'laboratory_city_id.required' => __('validation.required', ['attribute' => __('City')]),
            'laboratory_address.required' => __('validation.required', ['attribute' => __('Address')]),
            'contact_person_name.required' => __('validation.required', ['attribute' => __('Contact Person Name')]),
        ];
    }
}
