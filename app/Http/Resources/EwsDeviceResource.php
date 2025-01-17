<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EwsDeviceResource extends JsonResource
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
            'type' => $this->type,
            'province' => $this->province,
            'regency' => $this->regency,
            'district' => $this->district,
            'subdistrict' => $this->subdistrict,
            'address' => $this->address,
            'description' => $this->description,
            'address_histories' => EwsDeviceAddressHistoryResource::collection($this->addressHistories),
        ];
    }
}
