<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEwsDeviceMeasurementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'ews_device_id' => ['required', 'exists:ews_devices,id'],
            'vibration_value' => ['required', 'numeric'],
            'db_value' => ['required', 'numeric'],
        ];
    }
}
