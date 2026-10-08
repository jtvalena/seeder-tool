<?php

namespace App\Models;

use App\Http\Controllers\SharedFunctionController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminPurchaseOrderAmendment extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'admin_purchase_order_id',
        'transaction_number',
        'location_id',
        'vendor_id',
        'total_amount',
        'remarks',
        'status',
        'type',
        'rush',
        'prepared_by',
        'approve_admin_by',
        'updated_by',
        'company_id',
        'store_branch_id',
        'payment_method',
        'shipping_via_id',
        'vendor_address_id',
        'vendor_payment_item_id',
        'expected_date',
        'number_of_days',
        'is_overdue',
        'paid',
        'total_paid_amount',
        'total_billed_amount',
        'received',
        'amount_to_pay',
        'dr_number',
        'contact_person',
        'contact_number',
        'payment_type_id',
        'confirmed_by',
        'exec_approved_by',
        'confirmed_date',
        'admin_approved_date',
        'request_id',
        'customer_request',
        'customer_id',
        'soa_id',
        'billable',
        'requestor',
        'admin_warehouse_id',
        'admin_fas_basket_id',
        'exchange_deal',
        'amendment_type',
        'transmitted',
        'transmitted_by',
        'transmitted_date',
        'full_return',
        'filed',
        'filed_date',
    ];

    public function items()
    {
        return $this->hasMany(AdminPurchaseOrderAmendmentItem::class, 'admin_purchase_order_amendment_id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->transaction_number = SharedFunctionController::getPaddedSeriesWithYear(AdminPurchaseOrderAmendment::class, 'transaction_number', 4);
        });
    }
}
