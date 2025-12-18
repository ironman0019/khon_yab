<?php

namespace App\Http\Requests\Donor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBloodDonationRecordRequest extends FormRequest
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
            'donation_type' => ['required', 'integer', Rule::in([0, 1, 2])],
            'amount_ml' => ['required', 'integer', 'min:1', 'max:1000'],
            'donation_date' => ['required', 'date', 'before_or_equal:today'],
            'expiration_date' => ['nullable', 'date', 'after:donation_date'],
            'province_id' => ['nullable', 'integer', 'exists:provinces,id'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id', 'required_if:province_id,!=,null'],
            'notes' => ['nullable', 'string', 'max:1000'],
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
            'donation_type.required' => __('The donation type field is required.'),
            'donation_type.in' => __('The donation type must be Whole Blood, Plasma, or Platelets.'),
            'amount_ml.required' => __('The amount field is required.'),
            'amount_ml.min' => __('The amount must be at least 1 ml.'),
            'amount_ml.max' => __('The amount cannot exceed 1000 ml.'),
            'donation_date.required' => __('The donation date field is required.'),
            'donation_date.before_or_equal' => __('The donation date cannot be in the future.'),
            'expiration_date.after' => __('The expiration date must be after the donation date.'),
            'province_id.exists' => __('The selected province does not exist.'),
            'city_id.exists' => __('The selected city does not exist.'),
            'city_id.required_if' => __('The city field is required when province is selected.'),
            'notes.max' => __('The notes field cannot exceed 1000 characters.'),
        ];
    }
}

