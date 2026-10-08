<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PayrollDepartmentStatusApproval extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'payroll_department_status_id',
        'status_id',
        'approved_by',
        'approved_date',
    ];
}
