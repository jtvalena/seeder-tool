<?php

namespace App\Models;

use App\Models\EmployeeEmailTemp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeDataTemp extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'action',
        'employee_id',
        'employee_superior_id',
        'department_id',
        'client_id',
        'contract_type_id',
        'company_id',
        'citizenship_id',
        'religion_id',
        'marital_status_id',
        'job_title_id',
        'job_level_id',
        'store_branch_id',
        'status_id',
        'user_id',
        'salary_type_id',
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'place_of_birth',
        'gender',
        'age',
        'height',
        'weight',
        'gcash_num',
        'landline_num',
        'email',
        'employment_date',
        'sss_num',
        'tin_num',
        'philhealth_num',
        'pagibig_num',
        'active',
        'approved_by',
        'approved_date',
        'updated_by',
        'employee_image_base64',
        'employee_signature',
        'is_solo_parent',
    ];

    protected $appends = [
        'updated_by_name',
    ] ;

    public function employee_addresses(){
        return $this->hasMany(EmployeeDataAddressTemp::class,'employee_data_temp_id','id');
    }

    public function employee_emergency_contacts(){
        return $this->hasMany(EmployeeEmergencyContactsTemp::class,'employee_data_temp_id','id');
    }

    public function employee_family_members(){
        return $this->hasMany(EmployeeFamilyMembersTemp::class,'employee_data_temp_id','id');
    }

    public function employee_professional_references(){
        return $this->hasMany(EmployeeProfessionalReferencesTemp::class,'employee_data_temp_id','id');
    }

    public function employee_schools_attendeds(){
        return $this->hasMany(EmployeeSchoolAttendedTemp::class,'employee_data_temp_id','id');
    }

    public function employee_previous_employments(){
        return $this->hasMany(EmployeePreviousEmploymentsTemp::class,'employee_data_temp_id','id')->orderBy('year_month_from');;
    }

    public function mobile_number(){
        return $this->hasMany(EmployeeMobileNumTemp::class,'employee_data_temp_id','id');
    }

    public function emails(){
        return $this->hasMany(EmployeeEmailTemp::class,'employee_data_temp_id','id');
    }

    public function employee_salary(){
        return $this->hasMany(EmployeeSalaryTemp::class,'employee_data_temp_id','id');
    }

    public function getUpdatedByNameAttribute()
    {
        $updatedById = $this->updated_by;

        $updatedBy = User::find($updatedById);

        if ($updatedBy) {
            return $updatedBy->name;
        } else {
            return 'No Data';
        }
    }

    public function employee_data_file(){
        return $this->hasMany(EmployeeDataFileTemp::class, 'employee_data_temp_id', 'id');
    }
}
