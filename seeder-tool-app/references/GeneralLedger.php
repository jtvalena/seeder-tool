<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class GeneralLedger extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'vendor_id',
        'company_id',
        'store_branch_id',
        'transact_type_id',
        'transact_date',
        'transact_id',
        'transact_det_id',
        'coa_id',
        'particulars',
        'job_id',
        'department_id',
        'debit_amount',
        'credit_amount',
        'recognition_month',
        'transact_item_store_branch_id',
        'system_id',
    ];
}
