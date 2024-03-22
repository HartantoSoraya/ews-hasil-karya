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
            'addresses' => 'array',
            'addresses.*.address' => 'required|string|max:255',
        ];
    }

    public function prepareForValidation()
    {
        if (! $this->has('addresses')) {
            $this->merge(['addresses' => []]);
        }
    }
}
