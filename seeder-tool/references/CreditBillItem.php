<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CreditBillItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'credit_bill_id', 
        'vendor_payment_detail_id', 
        'particulars', 
        'amount', 
        'recognition_month', 
        'department_id', 
        'job_id', 
    ];
}
