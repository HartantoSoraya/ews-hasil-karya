<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEwsDeviceMeasurementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'device_code' => ['required', 'string', 'exists:ews_devices,code'],
            'vibration_value' => ['required', 'numeric'],
            'db_value' => ['required', 'numeric'],
        ];
    }
}
