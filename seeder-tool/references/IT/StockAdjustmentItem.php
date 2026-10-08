<?php

namespace App\Models\IT;


use Illuminate\Database\Eloquent\Model;
use App\Models\IT\StockAdjustment;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Items\Item;

class StockAdjustmentItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'stock_adjustment_id',
        'item_id',
        'qty_in',
        'qty_out',
    ];

    public function stock_adjustment()
    {
        return $this->belongsTo(StockAdjustment::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
