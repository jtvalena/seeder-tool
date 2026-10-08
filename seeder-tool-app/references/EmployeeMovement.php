<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeMovement extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'active',
        'active_movement',
        'department_id',
        'job_level_id',
        'job_title_id',
        'contract_type_id',
        'status_id',
        'approved_by',
        'approval_date',
        'effectivity_date',
        'movement_status_id',
        'overdue_reason',
        'company_id',
        'created_by',
        'client_id',
        'is_client_change',
        'store_branch_id',
        'employee_superior_id',
        'area',
        'has_history',
    ];

}
