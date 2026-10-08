<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Logistics\ProcurementPurchaseOrder;
use App\Models\Items\Item;

class ProcurementPurchaseOrderDetails extends Model
{
    protected $fillable = [
        'procurement_purchase_order_id',
        'item_id',
        'order_quantity',
        'previous_order_quantity',
        'auto_updated',
        'remarks'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(ProcurementPurchaseOrder::class, 'procurement_purchase_order_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
