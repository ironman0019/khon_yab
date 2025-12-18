<?php

namespace App\Http\Requests\Admin\InventoryManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBloodInventoryRequest extends FormRequest
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
        $inventoryId = $this->route('inventory_management');

        return [
            'bag_id' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('blood_inventories', 'bag_id')->ignore($inventoryId),
            ],
            'blood_donation_record_id' => ['sometimes', 'required', 'integer', 'exists:blood_donation_records,id'],
            'province_id' => ['sometimes', 'required', 'integer', 'exists:provinces,id'],
            'blood_type' => ['sometimes', 'required', 'string', Rule::in(['A', 'B', 'AB', 'O'])],
            'rh_factor' => ['sometimes', 'required', 'string', Rule::in(['positive', 'negative'])],
            'entry_date' => ['sometimes', 'required', 'date'],
            'exit_date' => ['nullable', 'date'],
            'expiration_date' => ['sometimes', 'required', 'date'],
            'status' => ['sometimes', 'required', 'integer'],
            'notes' => ['nullable', 'string'],
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
            'bag_id.required' => __('admin.The bag ID field is required.'),
            'bag_id.unique' => __('admin.The bag ID has already been taken.'),
            'blood_donation_record_id.required' => __('admin.The blood donation record ID field is required.'),
            'blood_donation_record_id.exists' => __('admin.The selected blood donation record does not exist.'),
            'province_id.required' => __('admin.The province field is required.'),
            'province_id.exists' => __('admin.The selected province does not exist.'),
            'blood_type.required' => __('admin.The blood type field is required.'),
            'blood_type.in' => __('admin.The selected blood type is invalid.'),
            'rh_factor.required' => __('admin.The RH factor field is required.'),
            'rh_factor.in' => __('admin.The selected RH factor is invalid.'),
            'entry_date.required' => __('admin.The entry date field is required.'),
            'exit_date.date' => __('admin.The exit date must be a valid date.'),
            'expiration_date.required' => __('admin.The expiration date field is required.'),
            'status.required' => __('admin.The status field is required.'),
        ];
    }
}
