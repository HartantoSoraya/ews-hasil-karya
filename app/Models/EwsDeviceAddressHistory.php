<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EwsDeviceAddressHistory extends Model
{
    use HasFactory, SoftDeletes, UUID;

    protected $fillable = [
        'ews_device_id',
        'province',
        'regency',
        'district',
        'subdistrict',
        'address',
    ];

    public function ewsDevice()
    {
        return $this->belongsTo(EwsDevice::class);
    }
}
