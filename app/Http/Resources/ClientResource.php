<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'email' => $this->user->email,
            'province' => $this->province,
            'regency' => $this->regency,
            'district' => $this->district,
            'subdistrict' => $this->subdistrict,
            'address' => $this->address,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
            'ews_devices' => EwsDeviceResource::collection($this->ewsDevices),
        ];
    }
}
