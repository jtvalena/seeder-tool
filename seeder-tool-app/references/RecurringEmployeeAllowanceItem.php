<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringEmployeeAllowanceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'recurring_employee_allowance_id',
        'allowance_type_id',
        'description',
        'rule_type',
        'frequency',
        'amount',
        'effectivity_date',
        'status',
    ];

    public function recurring_employee_allowance()
    {
        return $this->belongsTo(RecurringEmployeeAllowance::class, 'recurring_employee_allowance_id', 'id');
    }
}
