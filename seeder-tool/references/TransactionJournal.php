<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TransactionJournal extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'transaction_number',
        'transaction_journal_type_id',
        'vendor_id',
        'company_id',
        'store_branches_id',
        'bill_due',
        'terms',
        'total_amount',
        'remarks',
        'status',
        'currency_purchase_id'
    ];

    protected $appends = ['company_name', 'store_branch_name', 'transaction_journal_items'];

    // public function vendor_by_name()
    // {
    //     return $this->belongsTo(Vendor::class, 'vendor_id');
    // }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function store_branch()
    {
        return $this->belongsTo(StoreBranch::class, 'store_branches_id');
    }

    public function transaction_journal_items()
    {
        return $this->hasMany(TransactionJournalItem::class, 'transaction_journal_id')->orderBy('id');
    }
    public function getTransactionJournalItemsAttribute()
    {
        $details = TransactionJournalItem::
            select(
                'transaction_journals.transaction_number',
                'transaction_journal_items.particulars',
                'transaction_journal_items.amount',
                'transaction_journal_items.recognition_month',
                'transaction_journal_items.paid',
                'transaction_journal_types.name as transaction_journal_type_name',
                'companies.registered_name as company_name',
                'store_branches.name as store_branch_name',
                'vendors.company_name as vendor_name',
                'chart_of_accounts.account_name',
                'departments.name as department_name',
                'accounting_jobs.name as job_name',
            )
            ->leftJoin('transaction_journals', 'transaction_journal_items.transaction_journal_id','transaction_journals.id')
            ->leftJoin('transaction_journal_types', 'transaction_journals.transaction_journal_type_id','transaction_journal_types.id')
            ->leftJoin('vendors', 'transaction_journals.vendor_id','vendors.id')
            ->leftJoin('companies','transaction_journals.company_id','companies.id')
            ->leftJoin('store_branches','transaction_journals.store_branches_id','store_branches.id')
            ->leftJoin('chart_of_accounts','transaction_journal_items.coa_id','chart_of_accounts.id')
            ->leftJoin('departments','transaction_journal_items.department_id','departments.id')
            ->leftJoin('accounting_jobs','transaction_journal_items.job_id','accounting_jobs.id')
            ->where('transaction_journal_items.transaction_journal_id', $this->id)
            ->get();

        return $details;
    }

    // public function getVendorNameAttribute(){
    //     $name = !empty($this->vendor_by_name) ? $this->vendor_by_name->vendor_name : '';
    //     unset($this->vendor_by_name);
    //     return $name;
    // }

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
