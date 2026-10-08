<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeWithoutMovement extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_movement_id',
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
        'reason',
        'company_id',
        'created_by',
        'client_id',
        'is_client_change',
        'store_branch_id',
        'employee_superior_id',
        'area',
        'has_history',
        'done_change',
    ];

    public function employeeMovement(): BelongsTo
    {
        return $this->belongsTo(EmployeeMovement::class);
    }
}
