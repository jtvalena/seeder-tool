<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SnwHolidayEmployeeAllowance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'status_id',
        'deleted_by',
    ];

    public function items()
    {
        return $this->hasMany(SnwHolidayEmployeeAllowanceItem::class, 'snw_holiday_employee_allowance_id', 'id');
    }
}
