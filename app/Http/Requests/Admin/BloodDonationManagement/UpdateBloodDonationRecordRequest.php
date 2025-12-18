<?php

namespace App\Http\Requests\Admin\BloodDonationManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBloodDonationRecordRequest extends FormRequest
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
            'donor_id' => ['sometimes', 'required', 'integer', 'exists:donors,id'],
            'donation_type' => ['sometimes', 'required', 'integer', Rule::in([0, 1, 2])],
            'amount_ml' => ['sometimes', 'required', 'integer', 'min:1'],
            'donation_date' => ['sometimes', 'required', 'date'],
            'expiration_date' => ['sometimes', 'required', 'date', 'after:donation_date'],
            'province_id' => ['nullable', 'integer', 'exists:provinces,id'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id', 'required_if:province_id,!=,null'],
            'status' => ['sometimes', 'nullable', 'integer', Rule::in([0, 1, 2, 3])],
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
            'donor_id.required' => __('admin.The donor field is required.'),
            'donor_id.exists' => __('admin.The selected donor does not exist.'),
            'donation_type.required' => __('admin.The donation type field is required.'),
            'donation_type.in' => __('admin.The donation type must be Whole Blood, Plasma, or Platelets.'),
            'amount_ml.required' => __('admin.The amount field is required.'),
            'amount_ml.min' => __('admin.The amount must be at least 1 ml.'),
            'donation_date.required' => __('admin.The donation date field is required.'),
            'expiration_date.required' => __('admin.The expiration date field is required.'),
            'expiration_date.after' => __('admin.The expiration date must be after the donation date.'),
            'province_id.exists' => __('admin.The selected province does not exist.'),
            'city_id.exists' => __('admin.The selected city does not exist.'),
            'city_id.required_if' => __('admin.The city field is required when province is selected.'),
            'status.in' => __('admin.The status must be valid.'),
        ];
    }
}
