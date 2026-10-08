<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DispatchItemPl extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'dispatch_id',
        'dispatch_item_id',
        'packing_list_id',
        'so_id',
        'sales_order_id',
        'checker_form_id',
        'boxes',
        'not_loaded',
        'not_loaded_reason',
        'status',
    ];

    public function packing_list()
    {
        return $this->belongsTo(\App\Models\Sales\PackingList::class, 'packing_list_id');
    }

    public function dispatch()
    {
        return $this->belongsTo(\App\Models\Logistics\Dispatch::class);
    }

    public function dispatch_item()
    {
        return $this->belongsTo(\App\Models\Logistics\DispatchItem::class);
    }
}
