<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DispatchItemWpf extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'dispatch_item_wpfs';

    protected $fillable = [
        'dispatch_id',
        'dispatch_item_id',
        'wpf_id',
        'so_id',
        'sales_order_id',
        'boxes',
        'not_loaded',
        'not_loaded_reason',
        'status',
        'loaded',
    ];

    public function dispatch()
    {
        return $this->belongsTo(\App\Models\Logistics\Dispatch::class);
    }

    public function dispatch_item()
    {
        return $this->belongsTo(\App\Models\Logistics\DispatchItem::class);
    }

    public function wpf()
    {
        return $this->belongsTo(\App\Models\Operations\WarehousePreparationList::class, 'wpf_id');
    }

    public function packing_list()
    {
        return $this->hasOneThrough(
            \App\Models\Sales\PackingList::class,
            \App\Models\Operations\WarehousePreparationList::class,
            'id', // Foreign key on warehouse_preparation_lists table
            'wpf_id', // Foreign key on packing_lists table
            'wpf_id', // Local key on dispatch_item_wpfs table
            'id' // Local key on warehouse_preparation_lists table
        );
    }
}
