<?php

namespace App\Models;

use App\Models\Accounting\CustomerPaymentDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class FundTransfer extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'transaction_number',
        'fund_transfer_type_id',
        'vendor_id',
        'company_id',
        'store_branches_id',
        'bill_due',
        'terms',
        'total_amount',
        'remarks',
        'status',
        'prepard_by',
        'updated_by',
        'deleted_by',
        'approved_date',
        'approved_by',
        // 'currency_purchase_id',
        // 'source_bank_id',
        // 'destination_bank_id',
    ];
    public function fund_transfer_items()
    {
        return $this->hasMany(FundTransferItem::class, 'fund_transfer_id')->orderBy('id');
    }
    public function customer_payment_details()
    {
        return $this->hasMany(CustomerPaymentDetail::class, 'fund_transfer_id', 'id')->select('id as customer_payment_detail_id', 'fund_transfer_id');
    }
    protected $appends = ['company_name', 'store_branch_name', 'fund_transfer_items'];
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function store_branch()
    {
        return $this->belongsTo(StoreBranch::class, 'store_branches_id');
    }
    public function getFundTransferItemsAttribute()
    {
        $details = FundTransferItem::
            select(
                'fund_transfers.transaction_number',
                'fund_transfer_items.particulars',
                'fund_transfer_items.amount',
                'fund_transfer_items.recognition_month',
                'fund_transfer_items.paid',
                'fund_transfer_types.name as fund_transfer_type_name',
                'companies.registered_name as company_name',
                'store_branches.name as store_branch_name',
                'vendors.company_name as vendor_name',
                'chart_of_accounts.account_name',
                'departments.name as department_name',
                'accounting_jobs.name as job_name',
            )
            ->leftJoin('fund_transfers', 'fund_transfer_items.fund_transfer_id','fund_transfers.id')
            ->leftJoin('fund_transfer_types', 'fund_transfers.fund_transfer_type_id','fund_transfer_types.id')
            ->leftJoin('vendors', 'fund_transfers.vendor_id','vendors.id')
            ->leftJoin('companies','fund_transfers.company_id','companies.id')
            ->leftJoin('store_branches','fund_transfers.store_branches_id','store_branches.id')
            ->leftJoin('banks','fund_transfer_items.bank_id','banks.id')
            ->leftJoin('chart_of_accounts','banks.coa_id','chart_of_accounts.id')
            ->leftJoin('departments','fund_transfer_items.department_id','departments.id')
            ->leftJoin('accounting_jobs','fund_transfer_items.job_id','accounting_jobs.id')
            ->where('fund_transfer_items.fund_transfer_id', $this->id)
            ->get();

        return $details;
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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $lastTransactionNumber = static::withTrashed()->max('transaction_number');
            $model->transaction_number = str_pad($lastTransactionNumber ? ++$lastTransactionNumber : '00000001', 8, '0', STR_PAD_LEFT);
        });
    }
}
