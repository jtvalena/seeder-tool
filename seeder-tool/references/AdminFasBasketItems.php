<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


class AdminFasBasketItems extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;


    protected $fillable = [
        'admin_fas_basket_id',
        'vendor_id',
        'admin_fas_requisition_item_id',
        'capital_vendor_id',
        'quantity',
        'item_price',
        'total_amount',
        'approved_quantity',
        'buffer_stocks',
        'basket_canvassing_id',
        'item_id',
        'item_name',
        'uom_id',
        'paid',
        'item_vendor_price_list_id',
        'admin_warehouse_stocks_id',
        'received_qty',
        'pending_qty',
        'cancelled',
        'quantity_billed',
        'capitalization_paid',
        'admin_purchase_order_item_id',
    ];

    public function admin_fas_basket_sub_items(){
        return $this->hasMany(AdminFasBasketSubItem::class,'admin_fas_basket_item_id');
    }
}
