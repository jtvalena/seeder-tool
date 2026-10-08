<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AdminStoreOrderItem extends Model implements Auditable
{
    use HasFactory, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $appends = [
        // 'quantity',
    ];
    protected $table = 'admin_store_order_items';
    protected $fillable =[
        'admin_store_order_id',
        'admin_item_id',
        'paid',
        'foc',
        'priority',
        'total_amount',
        'company_id',
        'quantity',
        'item_paid_amount',
        'receive_qty',
        'pending_qty',
        'canceled_qty',
        'amount_to_pay',
        'original_price',
        'total_original_price',
        'basket_item_id',
        'item_vendor_price_list_id',
        'dr_price'

    ];


    public function prf_item()
    {
        // return $this->belongsTo(PurchaseRequestItem::class, 'prf_item_id');
    }

    // public function getQuantityAttribute()
    // {
    //     return $this->uom_text?$this->uom_text->name:'';
    // }

    public function admin_store_orders(){
        return $this->belongsTo(AdminStoreOrder::class,'id');
    }

    public function admin_item_name(){
        return $this->belongsTo(AdminItems::class, 'id');
    }

    public static function boot()
    {
        parent::boot();

        $cache_names = [
            'admin_store_order_stock_from_po',
            'selected_admin_store_order_stock_from_po',
            'admin_store_order_items',
            'store_order_number_asset_selection'
        ];
        static::created((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
         static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
            $sof = AdminStoreOrder::find($model->admin_store_order_id);
            if(!empty($sof->id)){
                $sof_items = AdminStoreOrderItem::where('admin_store_order_id', $sof->id)->get();
                if(count($sof_items) > 0){
                    $all_outbounded = $sof_items->every(function ($item) {
                        return $item->received_qty == $item->consumed_qty 
                            && $item->consumed_qty > 0 
                            && $item->received_qty > 0;
                    });
                    $partially_outbounded = $sof_items->contains(function ($item) {
                        return $item->received_qty == $item->consumed_qty 
                        && $item->consumed_qty > 0 
                        && $item->received_qty > 0;
                    });
                }
                if(!!$all_outbounded){
                    $sof->outbound_status = 3;
                } elseif(!!$partially_outbounded && !$all_outbounded) { 
                    $sof->outbound_status = 2;
                } else {
                    $sof->outbound_status = 1;
                }
                $sof->update();
            }
        }));
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }
}
