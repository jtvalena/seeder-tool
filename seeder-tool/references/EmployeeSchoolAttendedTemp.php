<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeSchoolAttendedTemp extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_data_temp_id',
        'school_attended',
        'field_course',
        'degree_earned',
        'year_from',
        'year_to',
    ];
}
