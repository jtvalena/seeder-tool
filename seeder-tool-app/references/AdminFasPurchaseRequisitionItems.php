<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminFasPurchaseRequisitionItems extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'fas_number',
        'fas_item_id',
        'quantity',
        'uom_id',
        'total_amount',
        'approved_quantity',
        'item_price',
        'admin_fpr_id',
        'item_name',
        'justification_remarks',
        'po_created',
        'outbound_qty',
        'item_vendor_price_list_id', 
        'admin_warehouse_stocks_id',
        'pending_qty',
        'quantity_billed',
        'capitalization_paid',
        'requested_qty',
        'admin_purchase_order_item_id',
        'capital_vendor_id',
        'capital_company_id',
        'capital_store_branch_id',
        'admin_warehouse_id',
    ];

    public function AdminFasPurchaseRequisition(){
        return $this->belongsTo(AdminFasPurchaseRequisition::class,'id');
    }

    protected static function boot()
    {
        parent::boot();
        static::updated(function ($item) {
            $parent = AdminFasPurchaseRequisition::find($item->admin_fpr_id);
            
            if (!empty($parent)) {
                if ($item->receive_qty > 0 && $item->sum('receive_qty') == $item->sum('outbound_qty')) {
                    $parent->status = 5;
                    $parent->saveQuietly();
                }
            }
        });
    }
}
