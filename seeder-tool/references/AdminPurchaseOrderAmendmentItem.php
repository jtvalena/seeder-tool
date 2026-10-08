<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminPurchaseOrderAmendmentItem extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'admin_purchase_order_amendment_id',
        'admin_purchase_order_item_id',
        'admin_item_id',
        'paid',
        'quantity',
        'pending_qty',
        'canceled_qty',
        'foc',
        'priority',
        'total_amount',
        'item_amount_to_pay',
        'item_paid_amount',
        'receive_qty',
        'original_price',
        'total_original_price',
        'request_item_id',
        'tagged',
        'received_qty',
        'consumed_qty',
        'basket_item_id',
        'item_vendor_price_list_id',
        'dr_price',
        'uom_id',
        'billed_amount',
    ];

    public function amendment()
    {
        return $this->belongsTo(AdminPurchaseOrderAmendment::class, 'id');
    }
}
