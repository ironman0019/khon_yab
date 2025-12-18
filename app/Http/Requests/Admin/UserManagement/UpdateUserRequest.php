<?php

namespace App\Http\Requests\Admin\UserManagement;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $user = $this->route('user_management');

        return [
            'full_name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user),
            ],
            'password' => ['sometimes', 'nullable', 'string', 'min:8', 'confirmed'],
            'user_type' => ['sometimes', 'required', 'integer', Rule::enum(UserType::class)],
            'is_admin' => ['sometimes', 'nullable', 'boolean'],
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
            'user_type.required' => __('admin.The user type field is required.'),
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
