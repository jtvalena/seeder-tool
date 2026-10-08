<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerTempAddress extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'customer_temp_id',
        'country_code',
        'region_code',
        'province_code',
        'city_code',
        'address',
        'contact_person',
        'contact_number',
        'type_address',
        'index'
    ];
}
