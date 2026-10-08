<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeDataAddressTemp extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_data_temp_id',
        'country_code',
        'region_code',
        'province_code',
        'city_code',
        'address',
        'address_type',
        'active',
    ];
}
