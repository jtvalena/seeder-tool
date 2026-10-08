<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Service\ScPartsOrder;
use App\Models\Items\Item;
use App\Models\Operations\Warehouse;
use App\Models\Operations\StockRequest;
use App\Models\Operations\StockTransfer;

class ScPartsOrderItem extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['unserved_qty', 'is_consumable', 'model', 'name', 'category', 'warehouse_code', 'stock_request_num', 'stock_transfer_num'];

    protected $fillable = [
        'sc_repair_form_id',
        'item_id',
        'selling_price',
        'total',
        'status',
        'return',
        'foc',
        'transfer_qty',
        'additional_qty',
        'requested_qty',
        'sc_parts_order_id',
        'additional',
        'return_damage_part',
    ];

    public function sc_parts_order()
    {
        return $this->belongsTo(ScPartsOrder::class);
    }

    public function sc_parts_order_item_files()
    {
        return $this->hasMany(ScPartsOrderItemFile::class, 'sc_parts_order_item_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function stockRequest()
    {
        return $this->belongsTo(StockRequest::class, 'stock_request_id');
    }

    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id');
    }

    public function getUnservedQtyAttribute()
    {
        return $this->requested_qty - $this->reserved_qty;
    }

    public function getIsConsumableAttribute()
    {
        return Item::where('id', $this->item_id)->value('consumable');
    }

    // ── Computed accessors (preserve API response shape) ─────────────────────

    public function getModelAttribute(): ?string
    {
        return $this->relationLoaded('item') ? ($this->item?->model) : null;
    }

    public function getNameAttribute(): ?string
    {
        return $this->relationLoaded('item') ? ($this->item?->name) : null;
    }

    public function getCategoryAttribute(): ?string
    {
        if (!$this->relationLoaded('item')) {
            return null;
        }
        $item = $this->item;
        return $item && $item->relationLoaded('itemCategory')
            ? ($item->itemCategory?->name)
            : null;
    }

    public function getWarehouseCodeAttribute(): ?string
    {
        return $this->relationLoaded('warehouse') ? ($this->warehouse?->code) : null;
    }

    public function getStockRequestNumAttribute(): ?string
    {
        return $this->relationLoaded('stockRequest')
            ? ($this->stockRequest?->stock_request_form_number)
            : null;
    }

    public function getStockTransferNumAttribute(): ?string
    {
        return $this->relationLoaded('stockTransfer')
            ? ($this->stockTransfer?->stock_transfer_form_number)
            : null;
    }
}
