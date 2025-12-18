<?php

namespace App\Http\Requests\Admin\LanguageManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTranslationRequest extends FormRequest
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
        $translation = $this->route('translation');

        return [
            'key' => ['required', 'string', 'max:255'],
            'group' => ['required', 'string', 'max:255'],
            'language_code' => ['required', 'string', 'max:10', Rule::exists('languages', 'code')],
            'value' => ['required', 'string'],
        ];
    }
}
