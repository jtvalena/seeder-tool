<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeSchoolsAttended extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;

    protected $fillable = [
        'employee_id',
        'school_attended',
        'field_course',
        'year_from',
        'year_to',
        'degree_earned',
    ];
}
