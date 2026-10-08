<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerDiscount extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'discount';

    protected $fillable = [
        'customer_id',
        'brand_id',
        'discount_type_id',
        'discount_value',
    ];
}
