<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringFixedEmployeeAllowance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'payroll_period_id',
        'status_id',
    ];

    public function items()
    {
        return $this->hasMany(RecurringFixedEmployeeAllowanceItem::class, 'recurring_fixed_employee_allowance_id', 'id');
    }
}
