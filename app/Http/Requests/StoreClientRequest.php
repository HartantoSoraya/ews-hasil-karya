<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'code' => 'required|string|max:255|unique:clients',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'province' => 'nullable|string|max:255',
            'regency' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'subdistrict' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'ews_devices' => 'nullable|array',
            'ews_devices.*' => 'required|exists:ews_devices,id',
        ];
    }

    public function prepareForValidation()
    {
        if (! $this->has('ews_devices')) {
            $this->merge(['ews_devices' => []]);
        }
    }
}
