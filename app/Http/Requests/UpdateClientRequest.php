<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'code' => 'required|string|max:255|unique:clients,code,'.$this->route('id').',id',
            'name' => 'required|string|max:255',

            'password' => 'nullable|string|min:8',
            'province' => 'nullable|string|max:255',
            'regency' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'subdistrict' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'ews_devices' => 'nullable|array',
            'ews_devices.*' => 'required|exists:ews_devices,id',
        ];
    }

    public function prepareForValidation()
    {
        if (! $this->has('province')) {
            $this->merge([
                'province' => '',
            ]);
        }

        if (! $this->has('regency')) {
            $this->merge([
                'regency' => '',
            ]);
        }

        if (! $this->has('district')) {
            $this->merge([
                'district' => '',
            ]);
        }

        if (! $this->has('subdistrict')) {
            $this->merge([
                'subdistrict' => '',
            ]);
        }

        if (! $this->has('address')) {
            $this->merge([
                'address' => '',
            ]);
        }

        if (! $this->has('phone')) {
            $this->merge([
                'phone' => '',
            ]);
        }

        if (! $this->has('description')) {
            $this->merge([
                'description' => '',
            ]);
        }

        $this->merge([
            'is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN),
        ]);

        if (! $this->has('ews_devices')) {
            $this->merge(['ews_devices' => []]);
        }
    }
}
