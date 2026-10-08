<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SnwHolidayEmployeeAllowanceItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'snw_holiday_employee_allowance_id',
        'allowance_type_id',
        'description',
        'amount',
        'effectivity_date',
        'deleted_by',
    ];

    public function snwHolidayAllowance()
    {
        return $this->belongsTo(SnwHolidayEmployeeAllowance::class, 'snw_holiday_employee_allowance_id', 'id');
    }
}
