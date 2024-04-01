<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEwsDeviceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'code' => 'required|string|max:255|unique:ews_devices',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'regency' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'subdistrict' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
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

        if (! $this->has('description')) {
            $this->merge([
                'description' => '',
            ]);
        }
    }
}
