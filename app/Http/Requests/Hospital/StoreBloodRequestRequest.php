<?php

namespace App\Http\Requests\Hospital;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBloodRequestRequest extends FormRequest
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
            'blood_type' => ['required', 'string', Rule::in(['A', 'B', 'AB', 'O'])],
            'rh_factor' => ['required', 'string', Rule::in(['positive', 'negative'])],
            'number_of_bags' => ['required', 'integer', 'min:1'],
            'patient_name' => ['required', 'string', 'max:255'],
            'patient_age' => ['required', 'integer', 'min:0', 'max:150'],
            'request_reason' => ['required', 'string'],
            'contact_number' => ['required', 'string', 'max:20'],
            'province_id' => ['required', 'integer', 'exists:provinces,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'medical_center' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
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
            'blood_type.required' => __('hospital.The blood type field is required.'),
            'blood_type.in' => __('hospital.The blood type must be A, B, AB, or O.'),
            'rh_factor.required' => __('hospital.The RH factor field is required.'),
            'rh_factor.in' => __('hospital.The RH factor must be positive or negative.'),
            'number_of_bags.required' => __('hospital.The number of bags field is required.'),
            'number_of_bags.min' => __('hospital.The number of bags must be at least 1.'),
            'patient_name.required' => __('hospital.The patient name field is required.'),
            'patient_age.required' => __('hospital.The patient age field is required.'),
            'patient_age.min' => __('hospital.The patient age must be at least 0.'),
            'patient_age.max' => __('hospital.The patient age cannot exceed 150.'),
            'request_reason.required' => __('hospital.The request reason field is required.'),
            'contact_number.required' => __('hospital.The contact number field is required.'),
            'province_id.required' => __('hospital.The province field is required.'),
            'province_id.exists' => __('hospital.The selected province does not exist.'),
            'city_id.required' => __('hospital.The city field is required.'),
            'city_id.exists' => __('hospital.The selected city does not exist.'),
            'medical_center.required' => __('hospital.The medical center field is required.'),
        ];
    }
}
