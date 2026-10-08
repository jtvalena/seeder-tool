<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ServicePaymentRequest extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'service_payment_request_number',
        'service_payment_request_type_id',
        'vendor_id',
        'company_id',
        'store_branches_id',
        'bill_due',
        'terms',
        'total_amount',
        'remarks',
        'status',
        'department_id',
        'prepared_by',
        'special_approved_by',
        'approved_by',
        'updated_by',
        'deleted_by',
        'payment_type_id',
        'rejected_by',
        'rejected_by_date',
        'petty_cash_budget_item_id',
        'dept_approved_by',
        'bank_card_id',
        'payable_to',
        'bank_account_number',
        'contact_number',
    ];

    protected $appends = [
        'company_name',
        'store_branch_name',
        'vendor_name',
        'user_by_name',
        'approved_by_name',
        'service_payment_request_items',
        'dept_approved_by_name',
        'store_branch_address',
    ];

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
    public function store_branch_address()
    {
        return $this->belongsTo(StoreBranch::class, 'store_branches_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }
    public function service_payment_request_items()
    {
        return $this->hasMany(ServicePaymentRequestItem::class, 'service_payment_request_id')->orderBy('id');
    }
    public function getServicePaymentRequestItemsAttribute()
    {
        $details = ServicePaymentRequestItem::
            select(
                'service_payment_requests.service_payment_request_number',
                'service_payment_request_types.name as service_payment_request_type_name',
                'companies.registered_name as company_name',
                'store_branches.name as store_branch_name',
                'vendors.company_name as vendor_name',
                'chart_of_accounts.account_name',
                'service_payment_request_items.particulars',
                'service_payment_request_items.amount',
                'service_payment_request_items.recognition_month',
                'service_payment_request_items.paid',
            )
            ->leftJoin('service_payment_requests','service_payment_request_items.service_payment_request_id','service_payment_requests.id')
            ->leftJoin('service_payment_request_types','service_payment_requests.service_payment_request_type_id','service_payment_request_types.id')
            ->leftJoin('vendors', 'service_payment_requests.vendor_id','vendors.id')
            ->leftJoin('companies','service_payment_requests.company_id','companies.id')
            ->leftJoin('store_branches','service_payment_requests.store_branches_id','store_branches.id')
            ->leftJoin('chart_of_accounts','service_payment_request_items.coa_id','chart_of_accounts.id')
            ->where('service_payment_request_items.service_payment_request_id', $this->id)
            ->get();

        return $details;
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
    public function getUserByNameAttribute()
    {
        $name = !empty($this->user) ? $this->user->name : '';
        unset($this->user);
        return $name;
    }

    public function getApprovedByNameAttribute()
    {
        return User::where('id', $this->approved_by)->value('name');
    }
    public function getDeptApprovedByNameAttribute()
    {
        return User::where('id', $this->dept_approved_by)->value('name');
    }
    public function getStoreBranchAddressAttribute(){
        $store_branch_address = !empty($this->store_branch_address) ? $this->store_branch_address->address : '';
        unset($this->store_branch_address);
        return $store_branch_address;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $lastServicePaymentRequestNumber = static::withTrashed()->max('service_payment_request_number');
            $model->service_payment_request_number = str_pad($lastServicePaymentRequestNumber ? ++$lastServicePaymentRequestNumber : '00000001', 8, '0', STR_PAD_LEFT);
        });
    }
    public function tableNameForUpload()
    {
        return 'ServicePaymentRequestFiles';
    }

    public function AutoJVTransactionNumber(){
        return 'service_payment_request_number';
    }

    public function AutoJVTransactionCode(){
        return 'SPR # ';
    }
}
