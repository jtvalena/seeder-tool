<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PayrollAdjustmentDetail extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'payroll_adjustment_id',
        'payroll_adjustment_type_id',
        'amount',
        'reason',
    ];

    protected $casts = [
        'amount' => 'decimal:12',
    ];

    public function payroll_adjustments(){
        return $this->belongsTo(PayrollAdjustment::class);
    }

    public function payroll_adjustment_type(){
        return $this->belongsTo(PayrollAdjustmentType::class, 'payroll_adjustment_type_id');
    }
}

