<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DispatchItemStockCard extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'dispatch_id',
        'dispatch_item_id',
        'stock_card_id',
        'sales_order_id',
        'boxes',
    ];
}
