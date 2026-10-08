<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerTempDiscount extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'customer_temp_id',
        'brand_id',
        'discount_type_id',
        'discount_value',
    ];
}
