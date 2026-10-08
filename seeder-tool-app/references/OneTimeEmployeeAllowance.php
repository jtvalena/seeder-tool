<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneTimeEmployeeAllowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_period_id',
        'status_id',
    ];

    public function items()
    {
        return $this->hasMany(OneTimeEmployeeAllowanceItem::class, 'one_time_employee_allowance_id', 'id');
    }
}
