<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepartmentAccessDelegation extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'department_access_delegations';
    
    protected $fillable = ['employee_id', 'department_id'];

    /**
     * Retrieve the employee who has department access.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeData::class, 'employee_id');
    }

    /**
     * Retrieve the department that is delegated.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
