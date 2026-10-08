<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class JournalVoucherManager extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'jvm_num', 
        'jvm_date', 
        'check_date', 
        'approved_date', 
        'post_date', 
        'check_no', 
        'status', 
        'vendor_id', 
        'company_id', 
        'store_branches_id', 
        'open_balance', 
        'remarks', 
        'prepared_by', 
        'checked_by', 
        'released_by',
        'cancelled_by',
        'cancelled_at',
        'journal_voucher_type_id',
    ];
    protected $appends = [
        'vendor_name', 
        'company_name', 
        'store_branch_name', 
        'prepared_by_name', 
        'checked_by_name', 
        'released_by_name', 
        'status_text', 
        'cancelled_by_name',
        'journal_voucher_manager_items'
    ];

    public function prepared_by_user()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }
    public function checked_by_user()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
    public function released_by_user()
    {
        return $this->belongsTo(User::class, 'released_by');
    }
    public function cancelled_by_user()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
    public function vendor_by_name()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function journal_voucher_manager_items()
    {
        return $this->hasMany(JournalVoucherManagerItem::class,'jvm_id')->orderBy('id');
    }
    public function company_by_name()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function store_branch_by_name()
    {
        return $this->belongsTo(StoreBranch::class, 'store_branches_id');
    }
    public function getPreparedByNameAttribute(){
        $name = !empty($this->prepared_by_user) ? $this->prepared_by_user->name : '';
        unset($this->prepared_by_user);
        return $name;
    }

    public function getCheckedByNameAttribute(){
        $name = !empty($this->checked_by_user) ? $this->checked_by_user->name : '';
        unset($this->checked_by_user);
        return $name;
    }
    public function getReleasedByNameAttribute(){
        $name = !empty($this->released_by_user) ? $this->released_by_user->name : '';
        unset($this->released_by_user);
        return $name;
    }
    public function getCancelledByNameAttribute(){
        $name = !empty($this->cancelled_by_user) ? $this->cancelled_by_user->name : '';
        unset($this->cancelled_by_user);
        return $name;
    }
    public function getVendorNameAttribute(){
        $code = !empty($this->vendor_by_name) ? $this->vendor_by_name->company_name : '';
        unset($this->vendor_by_name);
        return $code;
    }
    public function getCompanyNameAttribute()
    {
        $code = !empty($this->company_by_name) ? $this->company_by_name->registered_name : '';
        unset($this->company_by_name);
        return $code;
    }
    public function getStoreBranchNameAttribute(){
        $code = !empty($this->store_branch_by_name) ? $this->store_branch_by_name->name : '';
        unset($this->store_branch_by_name);
        return $code;
    }
    public function getStatusTextAttribute()
    {
        if($this->status==1){
            // return 'hold';
            return 'pending';
        }elseif($this->status==2){
            // return 'signing';
            return 'approved';
        }elseif($this->status==3){
            return 'cancelled';
        }elseif($this->status==4){
            return 'releasing';
        }elseif($this->status==5){
            return 'signed';
        }elseif($this->status==6){
            return 'released';
        }
    }
    public function getJournalVoucherManagerItemsAttribute()
    {
        $details = JournalVoucherManagerItem::select(
            'journal_voucher_manager_items.particulars',
            'journal_voucher_manager_items.debit_amount',
            'journal_voucher_manager_items.credit_amount',
            'journal_voucher_manager_items.recognition_month',
            'departments.name as department_name',
            'accounting_jobs.name as job_name',
        )
        ->leftJoin('journal_voucher_managers', 'journal_voucher_manager_items.jvm_id', 'journal_voucher_managers.id')
        ->leftJoin('departments', 'journal_voucher_manager_items.department_id', 'departments.id')
        ->leftJoin('accounting_jobs', 'journal_voucher_manager_items.job_id', 'accounting_jobs.id')
        ->where('journal_voucher_manager_items.jvm_id', $this->id)
        ->get();
        return $details;
    }

}
