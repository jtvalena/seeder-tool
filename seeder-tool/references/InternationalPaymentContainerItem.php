<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InternationalPaymentContainerItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'international_payment_id',
        'international_payment_items_id',
        'container_item_id',
        'unit_cost_in_foreign',
        'total_cost_in_foreign',
        'item_id'
    ];
}
