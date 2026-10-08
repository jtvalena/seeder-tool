<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\MasterData\Vendor;
use App\Models\Company;
use App\Models\StoreBranch;
use App\Models\EmployeeData;

class RecurringBillSchedule extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'company_id',
        'store_branches_id',
        'employee_id',
        'type',
        'vendor_id',
        'account_num',
        'particulars',
        'remarks',
        'address',
        'amount',
        'frequency_parameter',
        'frequency_time',
        'billing_period',
        'active',
        'frequency_type_id',
        'recurring_bill_type_id',
        'prepared_by',
        'updated_by',
        'account_name',
        'service_provider',
        'location_id',
        'admin_service_provider_id',
        'admin_service_provider_item_id',
        'recurring_bill_id',
    ];
    protected $appends = ['company_name', 'store_branch_name', 'vendor_name', 'employee_name', 'prepared_by_name', 'updated_by_name'];
    public function vendor_by_name()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function store_branch()
    {
        return $this->belongsTo(StoreBranch::class, 'store_branches_id');
    }
    public function employee()
    {
        return $this->belongsTo(EmployeeData::class, 'employee_id', 'employee_id');
    }
    public function prepared_by_user()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }
    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function getVendorNameAttribute(){
        $name = !empty($this->vendor_by_name) ? $this->vendor_by_name->company_name : '';
        unset($this->vendor_by_name);
        return $name;
    }
    public function getCompanyNameAttribute()
    {
        $name = !empty($this->company) ? $this->company->registered_name : '';
        unset($this->company);
        return $name;
    }
    public function getStoreBranchNameAttribute()
    {
        $name = !empty($this->store_branch) ? $this->store_branch->name : '';
        unset($this->store_branch);
        return $name;
    }
    public function getEmployeeNameAttribute()
    {
        $name = !empty($this->employee) ? $this->employee->first_name . ' ' . $this->employee->last_name : '';
        unset($this->employee);
        return $name;
    }
    public function getPreparedByNameAttribute()
    {
        $name = !empty($this->prepared_by_user) ? $this->prepared_by_user->name : '';
        unset($this->prepared_by_user);
        return $name;
    }
    public function getUpdatedByNameAttribute(){
        $name = !empty($this->updated_by_user) ? $this->updated_by_user->name : '';
        unset($this->updated_by_user);
        return $name;
    }
}
