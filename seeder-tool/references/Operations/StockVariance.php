<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Operations\StockRequestDetails;
use App\Models\Operations\StockTransferDetails;
use App\Models\Operations\Warehouse;
use App\Models\Items\Item;
class StockVariance extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_request_detail_id',
        'stock_transfer_detail_id',
        'item_id',
        'user_id',
        'variance',
        'requesting_warehouse_id',
        'serving_warehouse_id',
        'status',
        'transact_date',
        'prepared_uom_quantity',
        'requested_uom_quantity',
        'received_uom_quantity',
        'type'

    ];

    public function stockRequestDetails(): BelongsTo
    {
        return $this->belongsTo(StockRequestDetails::class, 'stock_request_detail_id');
    }

    public function stockTransferDetails(): BelongsTo
    {
        return $this->belongsTo(StockTransferDetails::class, 'stock_transfer_detail_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class,'item_id');
    }

    public function requestingWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class,'requesting_warehouse_id');
    }

    public function servingWarehouses(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class,'serving_warehouse_id');
    }
}
