<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringFixedEmployeeAllowanceItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'recurring_fixed_employee_allowance_id',
        'allowance_type_id',
        'description',
        'frequency',
        'amount',
        'effectivity_date',
    ];

    public function recurringFixedAllowance()
    {
        return $this->belongsTo(RecurringFixedEmployeeAllowance::class, 'recurring_fixed_employee_allowance_id', 'id');
    }
}
