<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerAddress extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'customer_address';

    protected $fillable = [
        'customer_id',
        'country_code',
        'region_code',
        'province_code',
        'city_code',
        'address',
        'contact_person',
        'contact_number',
        'type_address',
    ];
}
