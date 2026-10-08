<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class StoreCashInTemplate extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    protected $table = 'store_cash_in_detail_templates';
    protected $fillable = [
        'company_id', 
        'store_branch_id', 
        'coa_id', 
        'name', 
        'plus_minus'
    ];
    protected $appends = [
        'company_name',
        'store_branch_name',
        'coa_name'
    ];
    public function store_cash_in_details()
    {
        return $this->hasMany(StoreCashInDetail::class);
    }
    public function store_cash_in_detail_template_items()
    {
        return $this->hasMany(StoreCashInDetailTemplateItem::class, 'store_cash_in_detail_template_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function store_branch()
    {
        return $this->belongsTo(StoreBranch::class, 'store_branch_id');
    }
    public function chart_of_account()
    {
        return $this->belongsTo(ChartOfAccounts::class, 'coa_id');
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
    public function getCoaNameAttribute()
    {
        $name = !empty($this->chart_of_account) ? $this->chart_of_account->account_name : '';
        unset($this->chart_of_account);
        return $name;
    }
}
