<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EwsDeviceAddressHistoryResource extends JsonResource
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
            'created_at' => $this->created_at,
            'province' => $this->province,
            'regency' => $this->regency,
            'district' => $this->district,
            'subdistrict' => $this->subdistrict,
            'address' => $this->address,
        ];
    }
}
