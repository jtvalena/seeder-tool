<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class InternationalPayment extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'vendor_id',
        'broker_vendor_id',
        'company_id',
        'store_branch_id',
        'remarks',
        'total_amount',
        'total_cost',
        'gross_cost',
        'disc_cost',
        'prepared_by',
        'status',
        'container_cost',
        'adjustment_amount',
        'total_sea_freight',
        'total_adjustment',
        'bill_date',
        'transact_date',
        'total_bank_charges',
        'total_processing_fee',
    ];
}
