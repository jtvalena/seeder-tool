<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class FundTransferItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'fund_transfer_id',
        'bank_id',
        // 'coa_id',
        'particulars',
        'amount',
        'recognition_month',
        'department_id',
        'job_id',
        'paid',
        'customer_payment_detail_id',
    ];
}
