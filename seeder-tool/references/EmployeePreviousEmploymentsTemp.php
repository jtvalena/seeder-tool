<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeePreviousEmploymentsTemp extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_data_temp_id',
        'prev_employer_name',
        'year_month_from',
        'year_month_to',
        'monthly_salary',
        'reason_for_leaving',
        'previous_employment_position',
    ];
}
