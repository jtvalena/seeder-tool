<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ServicePaymentRequestTypeItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillables = [
        'service_payment_request_type_id',
        'coa_id',
        'particulars',
        'amount',
        'recognition_month',
        'department_id',
        'job_id'
    ];
}
