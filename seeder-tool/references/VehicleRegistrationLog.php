<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleRegistrationLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'path',
        'extension',
        'size',
        'upload_by',
        'expiration_date',
        'vehicle_id',
    ];
}
