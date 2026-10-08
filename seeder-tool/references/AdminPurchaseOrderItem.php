<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminPurchaseOrderItem extends Model implements Auditable
{
    use HasFactory, Caching, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = [
        // 'quantity',
    ];

    protected $fillable =[
        'admin_purchase_order_id',
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
        'received_qty',
        'dr_price',
        'reprint',
        'uom_id',
        'from_sysmtem_id',
        'transfered_by',
        'from_asset_id',
        'billed_amount',
    ];


    public function prf_item()
    {
        // return $this->belongsTo(PurchaseRequestItem::class, 'prf_item_id');
    }

    // public function getQuantityAttribute()
    // {
    //     return $this->uom_text?$this->uom_text->name:'';
    // }

    public function admin_purchase_orders(){
        return $this->belongsTo(AdminPurchaseOrder::class,'id');
    }

    public function admin_item_name(){
        return $this->belongsTo(AdminItems::class, 'id');
    }

    public static function boot()
    {
        parent::boot();

        $cache_names = [
            'admin_stock_from_po',
            'selected_admin_stock_from_po',
            'admin_puchase_order_items',
            'po_number_asset_selection'
        ];
        static::created((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
            $po = AdminPurchaseOrder::find($model->admin_purchase_order_id);
            if(!empty($po->id)){
                $po_items = AdminPurchaseOrderItem::where('admin_purchase_order_id', $po->id)->get();
                if(count($po_items) > 0){
                    $all_outbounded = $po_items->every(function ($item) {
                        return $item->received_qty == $item->consumed_qty 
                            && $item->consumed_qty > 0 
                            && $item->received_qty > 0;
                    });
                    $partially_outbounded = $po_items->contains(function ($item) {
                        return $item->received_qty == $item->consumed_qty 
                        && $item->consumed_qty > 0 
                        && $item->received_qty > 0;
                    });
                }
                if(!!$all_outbounded){
                    $po->outbound_status = 3;
                } elseif(!!$partially_outbounded && !$all_outbounded) { 
                    $po->outbound_status = 2;
                } else {
                    $po->outbound_status = 1;
                }
                $po->update();
            }
        }));
        
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }
}
