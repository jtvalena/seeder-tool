<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollPeriodCalamityDateCompany extends Model
{
    protected $table = 'payroll_period_calamity_date_companies';

    protected $fillable = [
        'calamity_date_id',
        'context',
        'company_classification_id',
    ];

    public function calamityDate()
    {
        return $this->belongsTo(PayrollPeriodCalamityDate::class, 'calamity_date_id');
    }
}
