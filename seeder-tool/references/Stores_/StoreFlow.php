<?php

namespace App\Models\Stores_;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class StoreFlow extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'store_name',
        'product_number',
        'product_name',
        'brand',
        'unit',
        'inbound_outbound_number',
        'business_number',
        'business_type',
        'uom',
        'qty_before_movement',
        'qty_of_movement',
        'qty_after_movement',
        'movement_date',
        'client_name',
        'remarks'
    ];
}
