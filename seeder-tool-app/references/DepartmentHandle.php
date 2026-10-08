<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentHandle extends Model
{
    protected $table = 'department_handles';

    protected $fillable = [
        'employee_id',
        'department_id',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeData::class, 'employee_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}

