<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;

class ContainerItemWarehouseReceipt extends Model
{
    protected $fillable = [
        'container_item_id',
        'warehouse_id',
        'quantity',
        'received_type',
    ];

    protected $casts = [
        'quantity'      => 'decimal:4',
        'received_type' => 'integer',
    ];

    public function containerItem()
    {
        return $this->belongsTo(ContainerItem::class, 'container_item_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
