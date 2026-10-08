<?php

namespace App\Models;

use App\Traits\Caching;
use App\Models\EmployeeAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\EmployeeFamilyMembers;
use App\Models\EmployeeSchoolsAttended;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use App\Models\EmployeeEmergencyContact;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\EmployeePreviousEmployment;
use App\Models\EmployeeOvertimeRecordItems;
use App\Models\EmployeeProfessionalReferences;
use Carbon\Carbon;

class EmployeeData extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;
    protected $connectionName = 'mysql';
    protected $table = 'employee_data';

    public function setConnectionName($connectionName)
    {
        $this->connectionName = $connectionName;
    }

    protected $fillable = [
        'first_name',
        'company_id',
        'middle_name',
        'last_name',
        'birth_date',
        'place_of_birth',
        'employment_date',
        'gender',
        'age',
        'marital_status_id',
        'height',
        'weight',
        'religion_id',
        'sss_num',
        'tin_num',
        'philhealth_num',
        'pagibig_num',
        'citizenship_id',
        'job_title_id',
        'landline_num',
        'email',
        'status_id',
        'employee_image_base64',
        'active',
        'department_id',
        'employee_id',
        'contract_type_id',
        'client_id',
        'employee_superior_id',
        'user_id',
        'job_level_id',
        'employee_signature',
        'gcash_num',
        'store_branch_id',
        'employment_start_date',
        'updated_by',
        'real_employee_id',
        'hold_date',
        'hold',
        'id_template_id',
        'is_solo_parent',
        'absorbed',
        'salary_type_id',
        'auto_convert_regular_hours',
        'fdt_employee_id',
        'absorbed',
        'area',
    ];

    protected $appends = [
        'updated_by_name',
        'employee_full_name',
        'personal_email',
        'bio_metric_api_ids',
        'isStore',
        'has_leave_current_day',
        'position_name',
        'company_name',
        'department_name',
        'status_name',
        'store_branch_name',
        'client_name',
        'six_months',
        'one_year',
        'has_pending',
        'user_name',
        'one_month',
        'three_months',
        'five_months',
        'user_name_sys',
        'department_code',
        'position_code',
        'contract_name',
        'blood_type_name',
        'employee_overtime_record_item_ids',
        'job_level_name',
        'salary_type_name',
        'department_handles_ids',
    ];


    public function employee_addresses(){
        return $this->hasMany(EmployeeAddress::class,'employee_id','id');
    }

    public function employee_emergency_contacts(){
        return $this->hasMany(EmployeeEmergencyContact::class,'employee_id','id');
    }

    public function employee_family_members(){
        return $this->hasMany(EmployeeFamilyMembers::class,'employee_id','id');
    }

    public function employee_professional_references(){
        return $this->hasMany(EmployeeProfessionalReferences::class,'employee_id','id');
    }

    public function employee_schools_attendeds(){
        return $this->hasMany(EmployeeSchoolsAttended::class,'employee_id','id');
    }

    public function employee_previous_employments(){
        return $this->hasMany(EmployeePreviousEmployment::class,'employee_id','id')->orderBy('year_month_from');;
    }

    public function employee_data_files()
    {
        return $this->hasMany(EmployeeDataFile::class,'employee_id','id');
    }
    public function employment_history()
    {
        return $this->hasMany(EmploymentHistory::class,'employee_id','id');
    }

    public function mobile_number(){
        return $this->hasMany(EmployeeMobileNumber::class,'employee_id','id');
    }

    public function emails(){
        return $this->hasMany(EmployeeEmail::class,'employee_id','id');
    }

    public function employee_salary(){
        return $this->hasMany(EmployeeSalary::class,'employee_id','id');
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

    public function getEmployeeFullNameAttribute(){
        $middle_name = is_null($this->middle_name) ? '' : substr($this->middle_name, 0, 1);
        $name = $this->last_name. ", " . $this->first_name . " ".  $middle_name . ".";
        return $name;
    }

    public function getPersonalEmailAttribute(){
        $personal_email = EmployeeEmail::getQuery()->where('email_type_id', 3)->where('employee_id', $this->id)->value('email');
        return $personal_email;
    }

    public function getBioMetricApiIdsAttribute(){
        $host = BioMetricApi::select('bio_schemas.id', 'bio_schemas.schema_name')
            ->where('bio_metric_apis.active', 1)
            ->leftJoin('bio_metric_api_ports', 'bio_metric_api_ports.host_id', 'bio_metric_apis.id')
            ->leftJoin('bio_schemas', 'bio_schemas.bio_metric_api_port_id', 'bio_metric_api_ports.id')
            ->get()
            ->filter(function ($q) {
                try {
                    $empExists = DB::connection($q->schema_name)->table('personnel_employee')
                        ->whereRaw('CAST(emp_code AS UNSIGNED) = ?', [(int)$this->real_employee_id])
                        ->exists();

                    if ($empExists) {
                        return $q;
                    }
                } catch (QueryException $e) {
                    if ($e->getCode() == 1040) {
                        Log::channel('biometrics')->info('Too many connections: ' . $e->getMessage() . $this->real_employee_id);
                    }else{
                        Log::channel('biometrics')->info($e->getMessage() . 'Schema:' . $q->schema_name);
                    }
                }
            });

        // $apiIDS = [];

        // foreach($host as $host_row){

        // }
        return $host;
    }

    public function getIsStoreAttribute(){
        $store_branch = StoreBranch::where('id', $this->store_branch_id)->exists();
        return $store_branch;
    }

    public function getEmployeeOvertimeRecordItemIdsAttribute(){
        return EmployeeOvertimeRecordItems::where('employee_id', $this->id)->get()->map(function ($item) {
            $item = $item->only(['id', 'status','employee_overtime_record_id', 'payroll_period_id', 'series', 'for_emergency', 'employee_id']);
            $item['reject_reason'] = '';
            return $item;
        });
    }

    public function employee_overtime_record_items(){
        return $this->hasMany(EmployeeOvertimeRecordItems::class, 'employee_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'employee_data',
            'employee_superior',
            'employee_data_dropdown',
            'employee_data_dropdown_with_asset'
        ];
        static::created((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }

    public function employee_data_file(){
        return $this->hasMany(EmployeeDataFile::class, 'employee_id', 'id');
    }

    public function employee_allowance_rates(){
        return $this->hasMany(EmployeeAllowanceRate::class, 'employee_id', 'id');
    }

    public function employee_govt_deductions(){
        return $this->hasMany(EmployeeGovernmentDeduction::class, 'employee_id', 'id');
    }

    public function employee_leave_schedules(){
        return $this->hasMany(LeaveRequest::class, 'employee_id', 'id');
    }

    public function employee_payment_methods(){
        return $this->hasMany(EmployeePaymentMethod::class, 'employee_id', 'id');
    }

    public function getHasLeaveCurrentDayAttribute(){
        $leave_current_day = LeaveRequest::where('employee_id', $this->id)
        ->whereDate('leave_requests.inclusive_date_from', '>=', now())
        ->whereDate('leave_requests.inclusive_date_to', '<=', now())
        ->first();
        return $leave_current_day;
    }

    public function employee_salary_movements(){
        return $this->hasMany(EmployeeSalaryMovement::class, 'employee_id', 'id')->orderBy('current_active', 'desc');
        // return $this->hasMany(EmployeeSalaryMovement::class, 'employee_id', 'id')->where('status', 2)->where('current_active', 0);
    }

    public function cash_advances(){
        return $this->hasMany(CashAdvance::class, 'employee_id', 'id');
    }

    public function salary_type(){
        return $this->belongsTo(EmployeeSalaryType::class, 'salary_type_id');
    }

    public function position(){
        return $this->belongsTo(JobTitle::class, 'job_title_id');
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function status(){
        return $this->belongsTo(EmploymentStatus::class, 'status_id');
    }

    public function store_branch(){
        return $this->belongsTo(StoreBranch::class);
    }

    public function client(){
        return $this->belongsTo(ClientsTable::class, 'client_id');
    }

    public function contract(){
        return $this->belongsTo(EmployeeContractType::class, 'contract_type_id');
    }

    public function job_level(){
        return $this->belongsTo(JobLevel::class);
    }

    public function getSalaryTypeNameAttribute(){
        return $this->salary_type ? $this->salary_type->name : 'none';
    }

    public function getPositionNameAttribute()
    {
        return $this->position ? $this->position->name : 'N/A';
    }

    public function getPositionCodeAttribute()
    {
        return $this->position ? $this->position->code : 'N/A';
    }

    public function getCompanyNameAttribute()
    {
        return $this->company ? $this->company->common_name : 'none';
    }

    public function getDepartmentNameAttribute()
    {
        return $this->department ? $this->department->name : null;
    }

    public function getDepartmentCodeAttribute()
    {
        return $this->department ? $this->department->code : null;
    }

    public function getStatusNameAttribute()
    {
        return $this->status && isset($this->status->name) ? $this->status->name : '';
    }

    public function getStoreBranchNameAttribute()
    {
        if (is_object($this->store_branch)) {
            return $this->store_branch->name ?: 'N/A';
        }

        return $this->store_branch ?: 'N/A';
    }

    public function getClientNameAttribute()
    {
        return $this->client ? $this->client->name : 'N/A';
    }

    public function getSixMonthsAttribute(){
        if($this->employment_date){
            $command = 6;
            return Carbon::parse($this->employment_date)->addMonths($command)->format('Y-m-d');
        }else{
            return '';
        }
    }

    public function getOneMonthAttribute(){
        if($this->employment_date){
            $command = 1;
            return Carbon::parse($this->employment_date)->addMonths($command)->format('Y-m-d');
        }else{
            return '';
        }
    }

    public function getThreeMonthsAttribute(){
        if($this->employment_date){
            $command = 3;
            return Carbon::parse($this->employment_date)->addMonths($command)->format('Y-m-d');
        }else{
            return '';
        }
    }

    public function getFiveMonthsAttribute(){
        if($this->employment_date){
            $command = 5;
            return Carbon::parse($this->employment_date)->addMonths($command)->format('Y-m-d');
        }else{
            return '';
        }
    }

    public function getOneYearAttribute(){
        if($this->employment_date){
            $command = 1;
            return Carbon::parse($this->employment_date)->addYear($command)->format('Y-m-d');
        }else{
            return '';
        }
    }

    public function getHasPendingAttribute(){
        $has_pending = EmployeeSalaryMovement::where('employee_id', $this->employee_id)
            ->where('status', 1)
            ->where('current_active', 0)
            ->count();

        return $has_pending > 0 ? 'yes' : 'no';
    }

    public function getUserNameAttribute(){
        if($this->user_id){
            return User::where('id', $this->user_id)->value('name');
        }else{
            return 'N\A';
        }
    }

    public function getUserNameSysAttribute(){
        if($this->user_id){
            return User::on($this->connectionName)->where('id', $this->user_id)->value('username');
        }else{
            return 'N\A';
        }
    }

    public function getContractNameAttribute(){
        return $this->contract ? $this->contract->name : null;
    }

    public function blood_type(){
        return $this->belongsTo(BloodType::class);
    }

    public function getBloodTypeNameAttribute(){
        return $this->blood_type ? $this->blood_type->code : null;
    }

    public function getJobLevelNameAttribute(){
        return $this->job_level ? $this->job_level->name : null;
    }

    public function employee_id_files(){
        return $this->hasMany(EmployeeIDFile::class, 'employee_id', 'id');
    }

    public function employee_allowances(){
        return $this->hasMany(EmployeeAllowance::class, 'employee_id', 'id');
    }

    public function employee_clearance(){
        return $this->hasMany(EmployeeClearance::class, 'employee_id', 'id');
    }

    public function department_handles(){
        return $this->belongsToMany(Department::class, 'department_handles', 'employee_id', 'department_id')
            ->withTimestamps();
    }

    public function getDepartmentHandlesIdsAttribute(){
        return $this->department_handles()->pluck('department_id')->toArray();
    }
}
