<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Service\ScRfEstimation;
use App\Models\Items\Item;
use App\Models\Operations\Warehouse;
use App\Models\Operations\StockRequest;
use App\Models\Operations\StockTransfer;

class ScRfEstItem extends Model implements Auditable
{
    protected $fillable = [
        'sc_rf_estimation_id',
        'item_id',
        'warehouse_id',
        'selling_price',
        'disc_price',
        'total',
        'amount',
        'stock',
        'requested_qty',
        'serve_qty',
        'additional_qty',
        'transfer_qty',
        'additional',
        'new_estimate',
        'return',
        'return_qty',
        'foc',
        'status',
        'sp_number',
        'sc_est_conversation_id',
        'special',
    ];

    protected $appends = [
        'model',
        'name',
        'category',
        'warehouse_code',
        'stock_request_num',
        'stock_transfer_num',
    ];

    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    // ── Relationships ────────────────────────────────────────────────────────

    public function sc_rf_estimation()
    {
        return $this->belongsTo(ScRfEstimation::class);
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
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

    public function sales_quotation_item()
    {
        return $this->hasOne(SalesQuotationItem::class, 'sc_rf_est_item_id', 'id');
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
        if (!$item) {
            return null;
        }
        return $item->relationLoaded('itemCategory')
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
