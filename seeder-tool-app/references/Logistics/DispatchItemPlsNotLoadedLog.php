<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Model;

class DispatchItemPlsNotLoadedLog extends Model
{
    protected $fillable = [
        'dispatch_item_pl_id',
        'dispatch_id',
        'dispatch_item_id',
        'packing_list_id',
        'so_id',
        'sales_order_id',
        'checker_form_id',
        'boxes',
        'not_loaded',
        'not_loaded_reason',
        'marked_at',
    ];

    protected $casts = [
        'marked_at' => 'datetime',
    ];

    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class);
    }

    public function packing_list()
    {
        return $this->belongsTo(\App\Models\Sales\PackingList::class, 'packing_list_id');
    }

    public function dispatch_item_pl()
    {
        return $this->belongsTo(DispatchItemPl::class, 'dispatch_item_pl_id');
    }
}
