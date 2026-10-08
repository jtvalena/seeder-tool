<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryTemp extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_data_temp_id',
        'monthly_rate',
        'cut_off_rate',
        'daily_rate',
        'hourly_rate',
    ];
}
