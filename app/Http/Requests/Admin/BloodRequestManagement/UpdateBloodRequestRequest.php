<?php

namespace App\Http\Requests\Admin\BloodRequestManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBloodRequestRequest extends FormRequest
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
            'blood_type' => ['sometimes', 'required', 'string', Rule::in(['A', 'B', 'AB', 'O'])],
            'rh_factor' => ['sometimes', 'required', 'string', Rule::in(['positive', 'negative'])],
            'number_of_bags' => ['sometimes', 'required', 'integer', 'min:1'],
            'patient_name' => ['sometimes', 'required', 'string', 'max:255'],
            'patient_age' => ['sometimes', 'required', 'integer', 'min:0', 'max:150'],
            'request_reason' => ['sometimes', 'required', 'string'],
            'contact_number' => ['sometimes', 'required', 'string', 'max:20'],
            'province_id' => ['sometimes', 'required', 'integer', 'exists:provinces,id'],
            'city_id' => ['sometimes', 'required', 'integer', 'exists:cities,id'],
            'medical_center' => ['sometimes', 'required', 'string', 'max:255'],
            'notes' => ['sometimes', 'nullable', 'string'],
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
            'blood_type.required' => __('admin.The blood type field is required.'),
            'rh_factor.required' => __('admin.The RH factor field is required.'),
            'number_of_bags.required' => __('admin.The number of bags field is required.'),
            'patient_name.required' => __('admin.The patient name field is required.'),
            'patient_age.required' => __('admin.The patient age field is required.'),
            'request_reason.required' => __('admin.The request reason field is required.'),
            'contact_number.required' => __('admin.The contact number field is required.'),
            'province_id.required' => __('admin.The province field is required.'),
            'city_id.required' => __('admin.The city field is required.'),
            'medical_center.required' => __('admin.The medical center field is required.'),
        ];
    }
}
