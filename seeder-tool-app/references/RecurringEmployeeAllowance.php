<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringEmployeeAllowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'status_id',
    ];

    public function items()
    {
        return $this->hasMany(RecurringEmployeeAllowanceItem::class, 'recurring_employee_allowance_id', 'id');
    }
}
