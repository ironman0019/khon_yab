<?php

namespace App\Http\Requests\Admin\BloodDonationManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBloodTestRequest extends FormRequest
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
            'hiv_result' => ['required', 'integer', Rule::in([0, 1])],
            'hbv_result' => ['required', 'integer', Rule::in([0, 1])],
            'hcv_result' => ['required', 'integer', Rule::in([0, 1])],
            'syphilis_result' => ['required', 'integer', Rule::in([0, 1])],
            'malaria_result' => ['required', 'integer', Rule::in([0, 1])],
            'test_date' => ['required', 'date'],
            'test_logs' => ['nullable', 'string'],
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
            'hiv_result.required' => __('admin.The HIV test result is required.'),
            'hiv_result.in' => __('admin.The HIV test result must be negative (0) or positive (1).'),
            'hbv_result.required' => __('admin.The HBV test result is required.'),
            'hbv_result.in' => __('admin.The HBV test result must be negative (0) or positive (1).'),
            'hcv_result.required' => __('admin.The HCV test result is required.'),
            'hcv_result.in' => __('admin.The HCV test result must be negative (0) or positive (1).'),
            'syphilis_result.required' => __('admin.The Syphilis test result is required.'),
            'syphilis_result.in' => __('admin.The Syphilis test result must be negative (0) or positive (1).'),
            'malaria_result.required' => __('admin.The Malaria test result is required.'),
            'malaria_result.in' => __('admin.The Malaria test result must be negative (0) or positive (1).'),
            'test_date.required' => __('admin.The test date is required.'),
            'test_date.date' => __('admin.The test date must be a valid date.'),
        ];
    }
}
