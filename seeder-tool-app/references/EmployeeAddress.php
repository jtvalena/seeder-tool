<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeAddress extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public $timestamps = true;

    protected $fillable = [
        'country_code',
        'region_code',
        'province_code',
        'city_code',
        'address',
        'address_type',
        'active',
    ];

}
