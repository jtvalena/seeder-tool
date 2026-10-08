<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePaymentRequestItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'service_payment_request_id',
        'coa_id',
        'paid',
        'particulars',
        'amount',
        'recognition_month',
        'department_id',
        'job_id',
        'store_branch_id',
        'ip_item_id',
    ];
}
