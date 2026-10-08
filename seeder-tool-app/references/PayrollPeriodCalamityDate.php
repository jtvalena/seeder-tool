<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayrollPeriodCalamityDate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payroll_period_id',
        'calamity_date',
        'waive_late',
        'waive_late_applies_to',
        'grant_whole_day',
        'grant_whole_day_applies_to',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'waive_late'       => 'boolean',
        'grant_whole_day'  => 'boolean',
    ];

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function departments()
    {
        return $this->hasMany(PayrollPeriodCalamityDateDepartment::class, 'calamity_date_id');
    }

    public function employees()
    {
        return $this->hasMany(PayrollPeriodCalamityDateEmployee::class, 'calamity_date_id');
    }

    public function waiveLateDepartments()
    {
        return $this->hasMany(PayrollPeriodCalamityDateDepartment::class, 'calamity_date_id')
            ->where('context', 'waive_late');
    }

    public function waiveLateEmployees()
    {
        return $this->hasMany(PayrollPeriodCalamityDateEmployee::class, 'calamity_date_id')
            ->where('context', 'waive_late');
    }

    public function grantWholeDayDepartments()
    {
        return $this->hasMany(PayrollPeriodCalamityDateDepartment::class, 'calamity_date_id')
            ->where('context', 'grant_whole_day');
    }

    public function grantWholeDayEmployees()
    {
        return $this->hasMany(PayrollPeriodCalamityDateEmployee::class, 'calamity_date_id')
            ->where('context', 'grant_whole_day');
    }

    public function companies()
    {
        return $this->hasMany(PayrollPeriodCalamityDateCompany::class, 'calamity_date_id');
    }

    public function storeBranches()
    {
        return $this->hasMany(PayrollPeriodCalamityDateStoreBranch::class, 'calamity_date_id');
    }

    public function waiveLateCompanies()
    {
        return $this->hasMany(PayrollPeriodCalamityDateCompany::class, 'calamity_date_id')
            ->where('context', 'waive_late');
    }

    public function waiveLateStoreBranches()
    {
        return $this->hasMany(PayrollPeriodCalamityDateStoreBranch::class, 'calamity_date_id')
            ->where('context', 'waive_late');
    }

    public function grantWholeDayCompanies()
    {
        return $this->hasMany(PayrollPeriodCalamityDateCompany::class, 'calamity_date_id')
            ->where('context', 'grant_whole_day');
    }

    public function grantWholeDayStoreBranches()
    {
        return $this->hasMany(PayrollPeriodCalamityDateStoreBranch::class, 'calamity_date_id')
            ->where('context', 'grant_whole_day');
    }
}
