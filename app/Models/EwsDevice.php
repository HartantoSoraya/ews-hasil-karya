<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EwsDevice extends Model
{
    use HasFactory, SoftDeletes, UUID;

    protected $fillable = [
        'code',
        'name',
        'type',
        'province',
        'regency',
        'district',
        'subdistrict',
        'address',
        'description',
    ];

    public function addressHistories()
    {
        return $this->hasMany(EwsDeviceAddressHistory::class);
    }

    public function measurements()
    {
        return $this->hasMany(EwsDeviceMeasurement::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_ews_device_pivot');
    }
}
