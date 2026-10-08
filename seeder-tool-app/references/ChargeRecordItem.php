<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ChargeRecordItem extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'charge_record_id',
        'payroll_period_detail_id',
        'payable_in_date',
        'amount',
        'paid',
        'skipped',
        'payroll_period_id',
    ];

    public function charge_record(){
        return $this->belongsTo(ChargeRecord::class);
    }
}
