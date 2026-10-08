<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentVoucherDetail extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'payment_voucher_id', 
        'transact_item_id', 
        'purchase_order_id', 
        'enter_bill_ref_num', 
        'jo_num', 
        'cef_num', 
        'particular', 
        'recognition_month', 
        'department_id', 
        'job_id', 
        'amount', 
        'coa_id', 
        'debit_amount', 
        'credit_amount', 
        'jv_id', 
        'withholding_tax', 
        'reduced',
        'sequence',
        'store_branch_id',
    ];
}
