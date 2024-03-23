<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EwsDeviceMeasurement extends Model
{
    use HasFactory, SoftDeletes, UUID;

    protected $fillable = [
        'ews_device_id',
        'vibration_value',
        'db_value',
    ];

    public function device()
    {
        return $this->belongsTo(EwsDevice::class, 'ews_device_id');
    }
}
