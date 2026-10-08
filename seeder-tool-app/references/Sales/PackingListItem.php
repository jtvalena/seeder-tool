<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Items\Item;
use App\Models\Sales\PackingList;
use App\Models\Sales\PromoItem;
use App\Models\Sales\SalesOrderItem;
use App\Models\User;
use App\Traits\Caching;
use OwenIt\Auditing\Contracts\Auditable;

class PackingListItem extends Model implements Auditable
{
    use SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['final_price', 'served_amount', 'stock_card_qty_remaining'];

    protected $fillable = [
        'packing_list_id',
        'item_id',
        'quantity',
        'quantity_served',
        'quantity_canceled',
        'srp',
        'disc_price',
        'total_amount',
        'foc',
        'override_amount',
        'amount',
        'so_item_id',
        'checker_form_item_id',
        'warehouse_id',
        'is_bundle',
        'promo_items_id',
        'backend_promo',
        'manual_foc',
        'bundle_group_id',
        'sorting',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function packing_list()
    {
        return $this->belongsTo(PackingList::class);
    }

    public function sales_order_item()
    {
        return $this->belongsTo(SalesOrderItem::class, 'so_item_id');
    }

    public function checker_form_item()
    {
        return $this->belongsTo(CheckerFormItem::class);
    }

    public function override_user()
    {
        return $this->belongsTo(User::class);
    }

    public function promo_item()
    {
        return $this->belongsTo(PromoItem::class, 'promo_items_id')->withTrashed();
    }

    public function getFinalPriceAttribute(){
        if(!empty($this->override_amount)){
            return $this->override_amount;
        }else{
            return $this->amount;
        }
    }

    public function getServedAmountAttribute(){
        if(!empty($this->final_price)){
            return $this->final_price * $this->quantity_served;
        }else{
            return $this->total_amount;
        }
    }

    public function getStockCardQtyRemainingAttribute(){
        return $this->quantity - $this->quantity_served - $this->quantity_canceled;
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'customer_items',
        ];
        static::created((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }
}
