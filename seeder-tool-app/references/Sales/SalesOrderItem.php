<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Items\Item;
use App\Models\Sales\PromoItem;
use App\Models\Sales\SalesOrder;
use App\Models\User;
use App\Models\Operations\WarehouseItemStocks;

use OwenIt\Auditing\Contracts\Auditable;

class SalesOrderItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['final_price', 'quantity_unserved'];

    protected $fillable = [
        'sales_order_id',
        'item_id',
        'quantity',
        'quantity_served',
        'srp',
        'disc_price',
        'total_amount',
        'foc',
        'override_amount',
        'is_bundle',
        'promo_items_id',
        'amount',
        'prepared_quantity',
        'sc_quantity',
        'quantity_canceled',
        'backend_promo',
        'parent_so_item_id',
        'manual_foc',
        'bundle_group_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function sales_order()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function override_user()
    {
        return $this->belongsTo(User::class);
    }

    public function promo_item()
    {
        return $this->belongsTo(PromoItem::class, 'promo_items_id')->withTrashed();
    }

    public function warehouse_item_stocks()
    {
        return $this->hasManyThrough(WarehouseItemStocks::class, Item::class, 'id', 'item_id', 'item_id', 'id');
    }

    public function getFinalPriceAttribute(){
        if(!empty($this->override_amount)){
            return $this->override_amount;
        }else{
            return $this->amount;
        }
    }

    public function getQuantityUnservedAttribute()
    {
        return $this->quantity - $this->quantity_served - $this->quantity_canceled;
    }

    public function getQtyToServeAttribute()
    {
        return $this->quantity - $this->quantity_served - $this->quantity_canceled;
    }
}
