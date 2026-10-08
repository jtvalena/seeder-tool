<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeSalaryMovement extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'salary_type_id',
        'user_id',
        'approved_by',
        'effectivity_date',
        'system_effectivity_date',
        'approved_date',
        'monthly_rate',
        'declared_salary',
        'status',
        'current_active',
        'applied',
        'printed'
    ];

    protected $appends = [
        'updated_by_name',
        'salary_type_name',
        'status_name'
    ];

    public function getUpdatedByNameAttribute(){
        return User::where('id', $this->user_id)->value('name');
    }

    public function getSalaryTypeNameAttribute(){
        return EmployeeSalaryType::where('id', $this->salary_type_id)->value('name');
    }

    public function getStatusNameAttribute(){
        switch($this->status){
            case 1:
                return 'pending';
            case 2:
                return 'approved';
            default:
                return '';
        }
    }

    public function employee_salary_movement_files(){
        return $this->hasMany(EmployeeSalaryMovementFile::class, 'employee_salary_movement_id', 'id');
    }
    // status
    // 1 - saved
    // 2 - approved
}
