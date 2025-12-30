<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMessageRequest extends FormRequest
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
            'recipient_user_type' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Allow -1 for admin or valid UserType enum values
                    if ($value == -1) {
                        return;
                    }
                    if (! Rule::enum(UserType::class)->passes($attribute, $value)) {
                        $namespace = $this->getTranslationNamespace();
                        $fail(__($namespace.'.The selected recipient user type is invalid.'));
                    }
                },
            ],
            'recipient_email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    // If admin is selected (value = -1), check for admin users
                    if ($this->recipient_user_type == -1) {
                        $recipient = User::where('email', $value)
                            ->where('is_admin', true)
                            ->first();
                    } else {
                        $recipient = User::where('email', $value)
                            ->where('user_type', $this->recipient_user_type)
                            ->first();
                    }

                    if (! $recipient) {
                        $namespace = $this->getTranslationNamespace();
                        $fail(__($namespace.'.The selected recipient does not exist with the given email and user type.'));

                        return;
                    }

                    if ($recipient->id === $this->user()->id) {
                        $namespace = $this->getTranslationNamespace();
                        $fail(__($namespace.'.You cannot send a message to yourself.'));
                    }
                },
            ],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ];
    }

    /**
     * Get the translation namespace based on the route.
     */
    protected function getTranslationNamespace(): string
    {
        $routeName = $this->route()?->getName() ?? '';

        if (str_starts_with($routeName, 'admin.')) {
            return 'admin';
        }
        if (str_starts_with($routeName, 'receiver.')) {
            return 'receiver';
        }
        if (str_starts_with($routeName, 'donor.')) {
            return 'donor';
        }
        if (str_starts_with($routeName, 'laboratory.')) {
            return 'laboratory';
        }

        return 'admin'; // Default to admin
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $namespace = $this->getTranslationNamespace();

        return [
            'recipient_user_type.required' => __($namespace.'.The recipient user type field is required.'),
            'recipient_email.required' => __($namespace.'.The recipient email field is required.'),
            'recipient_email.email' => __($namespace.'.The recipient email must be a valid email address.'),
            'recipient_email.exists' => __($namespace.'.The selected recipient does not exist with the given email and user type.'),
            'subject.max' => __($namespace.'.The subject may not be greater than :max characters.'),
            'message.required' => __($namespace.'.The message field is required.'),
        ];
    }
}
