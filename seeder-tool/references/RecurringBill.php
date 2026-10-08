<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class RecurringBill extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'recurring_bills';
    protected $fillable = [
        'recurring_bill_schedule_id',
        'contract',
        'company_id',
        'store_branches_id',
        'employee_id',
        'vendor_id',
        'account_num',
        'amount',
        'bill_date',
        'due_date',
        'reference_num',
        'remarks',
        'address',
        'recurring_bill_type_id',
        'status',
        'paid',
        'prepared_by',
        'updated_by',
        'admin_service_provider_id',
        'admin_service_provider_item_id',
        'department_id',
        'requestor_department_id',
        'approved_by',
        'approved_date',
        'checked_by',
        'checked_date',
        'admin_approved_by',
        'admin_approved_date',
        'deleted_by',
        'deleted_date',
        'reverted_by',
        'reverted_date',
    ];
    protected $appends = [
        'company_name', 
        'store_branch_name', 
        'employee_name', 
        'prepared_by_name', 
        'updated_by_name',
        'recurring_bill_details',
    ];
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
    public function recurring_bill_details()
    {
        return $this->hasMany(RecurringBillDetail::class, 'recurring_bill_id');
    }
    public function getRecurringBillDetailsAttribute()
    {
        $details = RecurringBillDetail::select(
                'recurring_bill_details.particulars',
                'recurring_bill_details.bill_date',
                'recurring_bill_details.due_date',
                'recurring_bill_details.amount',
                'recurring_bill_details.cleared'
            )
            ->where('recurring_bill_id', $this->id)
            ->get();

        return $details;
    }
    public function tableNameForUpload()
    {
        return 'RecurringBillUpload';
    }
}
