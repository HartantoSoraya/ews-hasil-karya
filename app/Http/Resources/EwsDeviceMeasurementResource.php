<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EwsDeviceMeasurementResource extends JsonResource
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
            'ews_device' => new EwsDeviceResource($this->ews_device),
            'value' => $this->value,
            'created_at' => $this->created_at,
        ];
    }
}
