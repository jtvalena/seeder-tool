<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ChargeRecordTypeItem extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillables = [
        'charge_record_type_id',
        'coa_id',
        'particulars',
        'amount',
        'recognition_month',
        'department_id',
        'job_id'
    ];
}
