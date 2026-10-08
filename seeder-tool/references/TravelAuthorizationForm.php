<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TravelAuthorizationForm extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'vendor_id',
        'company_id',
        'store_branches_id',
        'taf_number',
        'request_date',
        'travel_type',
        'travel_start_date',
        'travel_end_date',
        'general_purpose',
        'destination',
        'designation',
        'taf_amount',
        'sales_target',
        'remarks',
        'total_amount',
        'paid_amount',
        'amount_to_pay',
        'car_used',
        'car_model',
        'color_code',
        'plate_number',
        'rfid',
        'status',
        'paid',
        'prepared_by',
        'requested_by',
        'reviewed_by',
        'approved_by',
        'processed_by',
        'rejected_by',
        'travel_authorization_form_template_id',
        'approved_amount',
        'ap_approved_date',
        'expected_liquidated_date',
        'vehicle_id',
        'bank_account_name',
        'bank_account_number',
        'banking_type',
        'approved_date',
    ];
    public function travel_authorization_form_template_lists()
    {
        return $this->hasMany(TravelAuthorizationFormTemplateList::class, 'travel_authorization_form_id');
    }
    public function travel_authorization_form_beneficiaries()
    {
        return $this->hasMany(TravelAuthorizationFormBeneficiaries::class, 'taf_id');
    }
    public function employeeTravelBudget()
    {
        return $this->hasOne(EmployeeTravelBudget::class, 'taf_id');
    }
    public function preparedBy()
    {
        return $this->belongsTo(EmployeeData::class, 'prepared_by', 'user_id');
    }
    public function rejected_tafs()
    {
        return $this->hasMany(RejectedTaf::class, 'taf_id');
    }
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $lastTransactionNumber = static::withTrashed()->max('taf_number');
            $model->taf_number = str_pad($lastTransactionNumber ? ++$lastTransactionNumber : '000001', 6, '0', STR_PAD_LEFT);
            $model->status = 1;
        });
    }
}
