<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class StoreCashInDetailTemplateItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable  = [ 
        'store_cash_in_detail_template_id', 
        'coa_id', 
        'name', 
        'plus_minus',
        'store_cash_in_group_id',
        'bank_master_id',
        'sales_type_id',
        'default_deduction',
        'default_fin_charge',
        'default_ewt',
    ];
    protected $appends = [
        'coa_name'
    ];
    public function chart_of_account()
    {
        return $this->belongsTo(ChartOfAccounts::class, 'coa_id');
    }
    public function getCoaNameAttribute()
    {
        $name = !empty($this->chart_of_account) ? $this->chart_of_account->account_name : '';
        unset($this->chart_of_account);
        return $name;
    }
}
