<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmploymentHistory extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'status_id',
        'job_title_id',
        'hired_date',
        'retirement_date',
        'department_id',
        'job_level_id',
        'contract_type_id',
        'company_id',
        'effectivity_date',
        'client_id',
        'agency_start_date',
        'employee_movement_id',
        'store_branch_id',
        'employee_superior_id',
        'area',
    ];
}
