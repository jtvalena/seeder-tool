<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Items\Item;
use App\Models\Sales\SalesOrderItem;
use App\Models\Operations\StockCard;
use App\Models\Sales\PackingListItem;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockCardItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

   protected $appends = ['view_item', 'packing_list_qty', 'packing_list_qty_served', 'packing_list_qty_remaining'];

    protected $fillable = [
        'wp_list_id',
        'sales_order_item_id',
        'packing_list_item_id',
        'quantity_served',
        'quantity',
        'item_id',
        'stock_card_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function sales_order_item()
    {
        return $this->belongsTo(SalesOrderItem::class);
    }

    public function stock_card()
    {
        return $this->belongsTo(StockCard::class, 'stock_card_id');
    }

    public function warehouseItemStocks()
    {
        return $this->hasMany(WarehouseItemStocks::class, 'item_id');
    }

    public function packing_list_item()
    {
        return $this->belongsTo(PackingListItem::class, 'packing_list_item_id');
    }

    public function getViewItemAttribute()
    {
        return Item::select('id', 'model', 'name', 'type_id', 'uom_id', 'category_id', 'selling_price', 'sp_price')->where('id', $this->item_id)->first();
    }

    public function getPackingListQtyAttribute()
    {
        if (!empty($this->packing_list_item)) {
            $packing_list_qty = PackingListItem::find($this->packing_list_item_id)->quantity;
        } else {
            $packing_list_qty = 0;
        }
        return $packing_list_qty;
    }

    public function getPackingListQtyServedAttribute()
    {
        if (!empty($this->packing_list_item)) {
            $packing_list_qty = PackingListItem::find($this->packing_list_item_id)->quantity_served;
        } else {
            $packing_list_qty = 0;
        }
        return $packing_list_qty;
    }

    public function getPackingListQtyRemainingAttribute()
    {
        return $this->packing_list_qty - $this->packing_list_qty_served;
    }
}
