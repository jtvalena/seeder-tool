<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Items\Item;
use App\Models\Sales\SalesOrderItem;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehousePreparationListItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

   // protected $appends = ['stock'];

    protected $fillable = [
        'wp_list_id',
        'sales_order_item_id',
        'quantity_served',
        'check_quantity',
        'quantity',
        'item_id',
        'sc_quantity',
        'partially_reserve',
        'out_quantity',
        'bundle_group_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function sales_order_item()
    {
        return $this->belongsTo(SalesOrderItem::class);
    }

    public function warehouse_preparation_list()
    {
        return $this->belongsTo(WarehousePreparationList::class, 'wp_list_id');
    }

    public function warehouseItemStocks()
    {
        return $this->hasMany(WarehouseItemStocks::class, 'item_id');
    }
}
