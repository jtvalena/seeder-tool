<?php

namespace App\Models\Accounting;

use App\Models\Items\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class StoreStockTransferItem extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'store_stock_transfer_id',
        'item_id',
        'quantity',
        'price',
        'total_amount',
    ];

    protected $casts = [
        'quantity' => 'float',
        'price' => 'float',
        'total_amount' => 'float',
    ];

    protected $appends = [
        'item_name',
        'uom',
    ];

    public function transfer()
    {
        return $this->belongsTo(StoreStockTransfer::class, 'store_stock_transfer_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function getItemNameAttribute()
    {
        return $this->item?->name;
    }

    public function getUomAttribute()
    {
        return $this->item?->itemUom?->code;
    }
}

