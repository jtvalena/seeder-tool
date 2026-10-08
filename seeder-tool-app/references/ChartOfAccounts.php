<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\ChartOfAccountCompany;

class ChartOfAccounts extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;
    use HasFactory, SoftDeletes;
    protected $table = 'chart_of_accounts';
    protected $appends = ['companies', 'branches'];
    protected $fillable = [
        'code', 
        'account_name', 
        'parent_account_id', 
        'account_type_id', 
        'fundamental_id', 
        'account_group_id', 
        // 'open_balance', 
        'as_of', 
        'currency_id', 
        'contra_coa_id', 
        // 'entry_type_id', 
        'debit', 
        'credit', 
        'withholding_tax', 
        'default_ap_account', 
        'cash_flow_activity_id', 
    ];

    public $arr = [];

    public function child() {
        return $this->hasMany(ChartOfAccounts::class, 'parent_account_id');
    }

    public function parent() {
        return $this->belongsTo(ChartOfAccounts::class, 'parent_account_id');
    }

    public function children() {
        $company_id = request()->get('company_id');
        $store_branch_id = request()->get('store_branch_id');

        return $this->child()
            ->leftJoin('chart_of_account_companies', 'chart_of_accounts.id', 'chart_of_account_companies.coa_id')
            ->leftJoin('chart_of_account_branches', 'chart_of_accounts.id', 'chart_of_account_branches.coa_id')
            ->select(
                'chart_of_accounts.id', 
                'chart_of_accounts.code', 
                'chart_of_accounts.account_name as name', 
                'chart_of_accounts.parent_account_id', 
                'chart_of_accounts.contra_coa_id', 
                'chart_of_accounts.account_type_id', 
                'chart_of_accounts.fundamental_id', 
                'chart_of_accounts.account_group_id', 
                'chart_of_accounts.debit', 
                'chart_of_accounts.credit'
            )
            ->when(isset($company_id), function ($q) use ($company_id) {
                return $q->where('chart_of_account_companies.company_id', $company_id);
            })
            ->when(isset($store_branch_id), function ($q) use ($store_branch_id) {
                return $q->where('chart_of_account_branches.store_branch_id', $store_branch_id);
            })
            ->groupBy('chart_of_accounts.id')
            ->orderBy('chart_of_accounts.account_name')
            ->with(['children']);
    }

    public function getCompanyRelation(){
        return $this->hasMany(ChartOfAccountCompany::class, 'coa_id');
    }
    public function getBranchRelation(){
        return $this->hasMany(ChartOfAccountBranch::class, 'coa_id');
    }

    public function getCompaniesAttribute(){
        $this->arr = [];
        $company = $this->getCompanyRelation();
        if($company->get()->count() > 0){
            $this->getCompanyRelation()->get()->each(function($q){
                array_push($this->arr,$q->company_id);
            });
        }
        return $this->arr;
    }

    public function getBranchesAttribute(){
        $this->arr = [];
        $branch = $this->getBranchRelation();
        if($branch->get()->count() > 0){
            $this->getBranchRelation()->get()->each(function($q){
                array_push($this->arr,$q->store_branch_id);
            });
        }
        return $this->arr;
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'coa_selection',
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
}
