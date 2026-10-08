<?php

namespace App\Models;

use App\Models\Items\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class IndentOrderItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'indent_order_id',
        'item_id',
        'quantity',
        'quantity_served',
        'quantity_ongoing',
        'quantity_canceled',
        'quantity_remaining',
        'srp',
        'disc_price',
        'amount',
        'total_amount',
        'override_amount',
        'has_sq',
        'foc',
        'manual_foc',
    ];

    public function indent_order()
    {
        return $this->belongsTo(IndentOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
