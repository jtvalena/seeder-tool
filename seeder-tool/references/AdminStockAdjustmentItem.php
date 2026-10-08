<?php

namespace App\Models;

use App\Models\Items\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminStockAdjustmentItem extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'stock_adjustment_id',
        'item_id',
        'quantity',
        'qty_in',
        'qty_out',
        'check_in',
        'check_out',
        'accounted',
        'unaccounted',
    ];

    public function stock_adjustment()
    {
        return $this->belongsTo(AdminStockAdjustment::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
