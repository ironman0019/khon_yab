<?php

namespace App\Http\Requests\Admin\ReceiverManagement;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReceiverRequest extends FormRequest
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
        $receiver = $this->route('receiver_management');
        $userId = $receiver ? $receiver->user_id : null;

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
            'mobile_number' => ['sometimes', 'required', 'string', 'max:20'],
            'national_code' => [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('receivers', 'national_code')->ignore($receiver?->id),
            ],
            'age' => ['sometimes', 'required', 'integer', 'min:18', 'max:100'],
            'gender' => ['sometimes', 'required', 'string', Rule::in(['male', 'female', 'other'])],
            'province_id' => ['sometimes', 'required', 'integer', 'exists:provinces,id'],
            'city_id' => ['sometimes', 'required', 'integer', 'exists:cities,id'],
            'address' => ['sometimes', 'required', 'string'],
            'blood_type' => ['sometimes', 'required', 'string', Rule::in(['A', 'B', 'AB', 'O'])],
            'rh_factor' => ['sometimes', 'required', 'string', Rule::in(['positive', 'negative'])],
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
            'mobile_number.required' => __('admin.The mobile number field is required.'),
            'national_code.required' => __('admin.The national code field is required.'),
            'national_code.unique' => __('admin.The national code has already been taken.'),
            'age.required' => __('admin.The age field is required.'),
            'province_id.required' => __('admin.The province field is required.'),
            'city_id.required' => __('admin.The city field is required.'),
            'blood_type.required' => __('admin.The blood type field is required.'),
            'rh_factor.required' => __('admin.The RH factor field is required.'),
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
