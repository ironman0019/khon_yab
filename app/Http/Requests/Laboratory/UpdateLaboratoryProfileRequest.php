<?php

namespace App\Http\Requests\Laboratory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLaboratoryProfileRequest extends FormRequest
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
            'laboratory_name' => ['required', 'string', 'max:255'],
            'laboratory_code' => ['nullable', 'string', 'max:255'],
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
            'laboratory_name.required' => __('laboratory.The laboratory name field is required.'),
            'mobile_number.required' => __('laboratory.The mobile number field is required.'),
            'province_id.required' => __('laboratory.The province field is required.'),
            'province_id.exists' => __('laboratory.The selected province does not exist.'),
            'city_id.required' => __('laboratory.The city field is required.'),
            'city_id.exists' => __('laboratory.The selected city does not exist.'),
            'address.required' => __('laboratory.The address field is required.'),
        ];
    }
}
