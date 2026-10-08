<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeTravelBudget extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'transaction_number',
        'taf_id',
        'employee_accounted_id',
        'remarks',
        'status',
        'total_amount',
        'ap_approved_date'
    ];
    public function travel_authorization_form()
    {
        return $this->belongsTo(TravelAuthorizationForm::class, 'taf_id');
    }
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = EmployeeTravelBudget::select('transaction_number')->whereRaw('length(transaction_number)=8')->max('transaction_number');
            if(!empty($latest)){
                $next_num = ($latest != null) ? intval($latest) + 1: 1;
                $model->transaction_number = str_pad((string)$next_num, 8, '0', STR_PAD_LEFT);
            }else{
                $model->transaction_number = str_pad('1', 8, '0', STR_PAD_LEFT);
            }
            $model->prepared_by = Auth::id();
            $model->status = 1;
        });
    }
}
