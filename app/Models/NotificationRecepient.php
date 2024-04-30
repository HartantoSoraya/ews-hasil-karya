<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UUID;

class NotificationRecepient extends Model
{
    use HasFactory, UUID, SoftDeletes;

    protected $fillable = [
        'name',
        'phone_number',
        'job_title',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
