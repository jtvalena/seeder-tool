<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Models\Operations\StockTransfer;
use App\Models\Operations\WarehouseItemStocks;
use App\Models\Items\Item;
class StockTransferDetails extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'stock_transfer_id',
        'item_id',
        'remaining_uom_quantity',
        'prepared_uom_quantity',
        'prepared_master_box_quantity',
        'received_uom_quantity',
        'variance',
        'remarks',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function stockTransfer(): BelongsTo
    {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function warehouseItemStocks()
    {
        return $this->hasMany(WarehouseItemStocks::class, 'item_id', 'item_id');
    }
}
