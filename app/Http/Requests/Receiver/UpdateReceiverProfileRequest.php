<?php

namespace App\Http\Requests\Receiver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReceiverProfileRequest extends FormRequest
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
        $receiver = auth()->user()->receiver;

        return [
            'full_name' => ['nullable', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'max:20'],
            'age' => ['required', 'integer', 'min:18', 'max:120'],
            'province_id' => ['required', 'integer', 'exists:provinces,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:1000'],
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
            'mobile_number.required' => __('The mobile number field is required.'),
            'mobile_number.max' => __('The mobile number cannot exceed 20 characters.'),
            'age.required' => __('The age field is required.'),
            'age.integer' => __('The age must be a number.'),
            'age.min' => __('The age must be at least 18.'),
            'age.max' => __('The age cannot exceed 120.'),
            'province_id.required' => __('The province field is required.'),
            'province_id.exists' => __('The selected province does not exist.'),
            'city_id.required' => __('The city field is required.'),
            'city_id.exists' => __('The selected city does not exist.'),
            'address.required' => __('The address field is required.'),
            'address.max' => __('The address cannot exceed 1000 characters.'),
            'blood_type.required' => __('The blood type field is required.'),
            'rh_factor.required' => __('The RH factor field is required.'),
        ];
    }
}
