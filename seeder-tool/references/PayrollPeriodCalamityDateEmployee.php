<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayrollPeriodCalamityDateEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'calamity_date_id',
        'context',
        'employee_id',
    ];

    public function calamityDate()
    {
        return $this->belongsTo(PayrollPeriodCalamityDate::class, 'calamity_date_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeData::class, 'employee_id', 'employee_id');
    }
}
