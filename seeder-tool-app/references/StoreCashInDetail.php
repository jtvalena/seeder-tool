<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class StoreCashInDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        'store_cash_in_id', 
        'store_cash_in_template_id', 
        'name',
        'coa_id',
        'plus_minus',
        'audited_date',
        'total_amount',
        'sales_amount',
        'fin_charge_amount',
        'ewt_amount',
        'store_cash_in_group_id',
        'bank_master_id',
        'sales_type_id',
    ];

    public function store_cash_in_template()
    {
        return $this->belongsTo(StoreCashInTemplate::class, 'store_cash_in_template_id');
    }
}
