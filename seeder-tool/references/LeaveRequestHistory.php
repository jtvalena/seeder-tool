<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class LeaveRequestHistory extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        'leave_request_id',
        'leave_type_id',
        'leave_type_used_on_id',
        'leave_type_used_on_day_w_pay',
        'employee_id',
        'approved_by',
        'approved_date',
        'confirmed_by',
        'confirmed_date',
        'rejected_by',
        'rejected_date',
        'reject_reason',
        'cancelled_at',
        'cancel_reason',
        'pro_rated',
        'status',
        'date_filed',
        'days_with_pay',
        'days_without_pay',
        'total_days',
        'inclusive_date_from',
        'inclusive_date_to',
        'reason',
        'others',
        'deleted_at',
        'with_pay',
    ];

    protected $appends = [
        'leave_type_name',
        'leave_dates'
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d', // Cast 'date_punched' as date in YYYY-MM-DD format
    ];

    public function leave_requests(){
        return $this->belongsTo(LeaveRequest::class);
    }

    public function getLeaveTypeNameAttribute(){
        $leave_type_id = $this->leave_type_id;
        if(is_null($leave_type_id)){
            return "";
        }
        $leave_type_name = LeaveType::where('id', $this->leave_type_id)->value('name');
        return $leave_type_name;
    }

    public function getLeaveDatesAttribute(){
        $inclusive_date_from = Carbon::parse($this->inclusive_date_from)->format('Y-m-d');
        $inclusive_date_to = Carbon::parse($this->inclusive_date_to)->format('Y-m-d');
        if($inclusive_date_from == $inclusive_date_to){
            return $inclusive_date_from;
        }
        return $inclusive_date_from. " - " . $inclusive_date_to;
    }
}
