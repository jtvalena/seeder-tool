<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ChargeRecord extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'doc_num',
        'employee_id',
        'payable_in',
        'start_deduction_date',
        'deduction_amount',
        'total_amount',
        'remarks',
        'charge_record_type_id',
        'prepared_by',
        'updated_by',
        'department_id',
        'status'
    ];

    protected $appends = [
        'paid_amount',
        'remaining_amount',
    ];

    public function charge_record_items(){
        return $this->hasMany(ChargeRecordItem::class, 'charge_record_id', 'id');
    }

    public function getPaidAmountAttribute(){
        $paid_amount = 0;
        $charge_record_items = $this->charge_record_items->where('paid', 1)->where('skipped', 0);
        foreach ($charge_record_items as $item) {
            $paid_amount += $item->amount;
        }
        return $paid_amount;
    }

    public function getRemainingAmountAttribute(){
        $remaining_amount = 0;
        $remaining_amount = $this->total_amount - $this->paid_amount;
        return $remaining_amount;
    }

    public function getPayrollStartDateAttribute(){
        $payroll_period = PayrollPeriod::find($this->payroll_period_id);
        return $payroll_period->date_from ? $payroll_period->date_from : null;
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $latest = ChargeRecord::max('doc_num');
            if(!empty($latest)){
                $next_doc_num = intval(substr($latest, -5)) + 1;
                $model->doc_num = str_pad((string)$next_doc_num, 5, '0', STR_PAD_LEFT);
            }else{
                $model->doc_num = str_pad('1', 5, '0', STR_PAD_LEFT);
            }
            $model->status = 1;
        });
    }
}
