<?php

namespace App\Http\Requests\Admin\LaboratoryManagement;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLaboratoryRequest extends FormRequest
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
        $laboratory = $this->route('laboratory_management');
        $userId = $laboratory ? $laboratory->user_id : null;

        return [
            'full_name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($userId),
            ],
            'password' => ['sometimes', 'nullable', 'string', 'min:8', 'confirmed'],
            'laboratory_name' => ['sometimes', 'required', 'string', 'max:255'],
            'laboratory_code' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('laboratories', 'laboratory_code')->ignore($laboratory?->id),
            ],
            'mobile_number' => ['sometimes', 'required', 'string', 'max:20'],
            'phone_number' => ['sometimes', 'nullable', 'string', 'max:20'],
            'province_id' => ['sometimes', 'required', 'integer', 'exists:provinces,id'],
            'city_id' => ['sometimes', 'required', 'integer', 'exists:cities,id'],
            'address' => ['sometimes', 'required', 'string'],
            'license_number' => ['sometimes', 'nullable', 'string', 'max:255'],
            'contact_person_name' => ['sometimes', 'required', 'string', 'max:255'],
            'status' => ['sometimes', 'nullable', 'integer', Rule::in([0, 1, 2, 3])],
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
            'password.min' => __('admin.The password must be at least 8 characters.'),
            'password.confirmed' => __('admin.The password confirmation does not match.'),
            'laboratory_name.required' => __('admin.The laboratory name field is required.'),
            'laboratory_code.required' => __('admin.The laboratory code field is required.'),
            'laboratory_code.unique' => __('admin.The laboratory code has already been taken.'),
            'mobile_number.required' => __('admin.The mobile number field is required.'),
            'province_id.required' => __('admin.The province field is required.'),
            'city_id.required' => __('admin.The city field is required.'),
            'address.required' => __('admin.The address field is required.'),
            'contact_person_name.required' => __('admin.The contact person name field is required.'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('password') && empty($this->password)) {
            $this->merge(['password' => null]);
        }
    }
}
