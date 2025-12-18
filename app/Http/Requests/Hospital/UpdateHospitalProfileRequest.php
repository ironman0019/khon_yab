<?php

namespace App\Http\Requests\Hospital;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHospitalProfileRequest extends FormRequest
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
            'hospital_name' => ['required', 'string', 'max:255'],
            'hospital_code' => ['nullable', 'string', 'max:255'],
            'license_number' => ['nullable', 'string', 'max:255'],
            'contact_person_name' => ['nullable', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'max:20'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'province_id' => ['required', 'integer', 'exists:provinces,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:1000'],
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
            'hospital_name.required' => __('hospital.The hospital name field is required.'),
            'mobile_number.required' => __('hospital.The mobile number field is required.'),
            'province_id.required' => __('hospital.The province field is required.'),
            'province_id.exists' => __('hospital.The selected province does not exist.'),
            'city_id.required' => __('hospital.The city field is required.'),
            'city_id.exists' => __('hospital.The selected city does not exist.'),
            'address.required' => __('hospital.The address field is required.'),
        ];
    }
}
