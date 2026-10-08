<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollPeriodCalamityDateStoreBranch extends Model
{
    protected $table = 'payroll_period_calamity_date_store_branches';

    protected $fillable = [
        'calamity_date_id',
        'context',
        'store_branch_id',
    ];

    public function calamityDate()
    {
        return $this->belongsTo(PayrollPeriodCalamityDate::class, 'calamity_date_id');
    }
}
