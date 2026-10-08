<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeDeduction extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'reference_num',
        'employee_id',
        'deduction_type_id',
        'status_id',
        'total_amount',
        'approved_by',
        'approved_date',
        'confirmed_by',
        'confirmed_date',
        'remarks',
        'payable_in',
        'start_deduction_date',
        'deduction_amount',
        'vendor_id',
        'store_branch_id',
        'company_id',
    ];

    protected $appends = [
        'employee_full_name',
        'remaining_amount',
        'total_paid_amount',
    ];

    protected $casts = [
        'employee_id' => 'int',
        'deduction_type_id' => 'int',
    ];

    public function getEmployeeFullNameAttribute(){
        $employee_data = EmployeeData::find($this->employee_id)->setAppends([
            'employee_full_name',
        ]);
        $middle_name = is_null($employee_data->middle_name) ? '' : substr($employee_data->middle_name, 0, 1);
        $name = $employee_data->last_name. ", " . $employee_data->first_name . " ".  $middle_name . ".";
        return $name;
    }

    public function getRemainingAmountAttribute(){
        $paid_amount = $this->employee_deduction_details()
            ->where('deducted', 1)->sum('amount');

        $remaining_amount = $this->total_amount - $paid_amount;

        return $remaining_amount;
    }

    public function getTotalPaidAmountAttribute(){
        $paid_amount = $this->employee_deduction_details()
            ->where('deducted', 1)->sum('amount');

        return $paid_amount;
    }

    public function employee_deduction_details(){
        return $this->hasMany(EmployeeDeductionDetail::class, 'employee_deduction_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $latest = EmployeeDeduction::max('reference_num');
            if(!empty($latest)){
                $next_reference_num = intval(substr($latest, -5)) + 1;
                $model->reference_num = str_pad((string)$next_reference_num, 5, '0', STR_PAD_LEFT);
            }else{
                $model->reference_num = str_pad('1', 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
