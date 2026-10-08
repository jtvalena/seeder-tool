<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAllowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_period_detail_id',
        'employee_id',
        'allowance_type_id',
        'amount',
        'status_id',
    ];
}
