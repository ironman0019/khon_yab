<?php

namespace App\Http\Requests\Admin\ProvinceManagement;

use App\Models\Province;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProvinceRequest extends FormRequest
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
        $province = $this->route('province_management');

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique(Province::class)->ignore($province),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('admin.The province name field is required.'),
            'name.unique' => __('admin.The province name has already been taken.'),
        ];
    }
}
