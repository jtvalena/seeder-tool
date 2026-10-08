<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Traits\TimekeepingTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequest extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use TimekeepingTrait;

    protected $fillable = [
        'leave_type_id',
        'payroll_period_id',
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
        'pro_rated',
        'leave_type_used_on_id',
        'leave_type_used_on_day_w_pay',
        'created_by',
        'series',
        'unpaid'
    ];

    protected $appends = [
        'approved_by_name',
        'confirmed_by_name',
        'rejected_by_name',
        'leave_type_name',
        'leave_status_name',
        'inclusive_dates_view',
        'created_by_name',
        'leave_type_used_on_name',
        'for_emergency',
    ] ;

    public function leave_request_files(){
        return $this->hasMany(LeaveRequestFile::class, 'leave_request_id', 'id');
    }

    public function leave_request_history(){
        return $this->hasMany(LeaveRequestHistory::class, 'leave_request_id', 'id');
    }

    public function leave_request_changes(){
        return $this->hasMany(LeaveRequestChanges::class, 'leave_request_id', 'id');
    }

    public function getApprovedByNameAttribute()
    {
        $approveById = $this->approved_by;

        $approveBy = User::find($approveById);

        if ($approveBy) {
            return $approveBy->name;
        } else {
            return '';
        }
    }

    public function getConfirmedByNameAttribute()
    {
        $confirmedById = $this->confirmed_by;

        $confirmed_by = User::find($confirmedById);
        if ($confirmed_by) {
            return $confirmed_by->name;
        } else {
            return '';
        }
    }

    public function getLeaveTypeNameAttribute(){
        $leave_type_id = $this->leave_type_id ;

        $leave_type_name = LeaveType::where('id', $leave_type_id)->value('name');
        return $leave_type_name ?? '';
    }

    public function getLeaveTypeUsedOnNameAttribute(){
        $leave_type_id = $this->leave_type_used_on_id ;

        $leave_type_name = LeaveType::where('id', $leave_type_id)->value('name');
        return $leave_type_name ?? '';
    }

    public function getLeaveStatusNameAttribute(){
        switch($this->status){
            case 1:
                return 'saved';
            case 2:
                return 'approved';
            case 3:
                return 'confirmed';
            case 4:
                return 'rejected';
            case 5:
                return 'cancelled';
        }
    }

    public function getInclusiveDatesViewAttribute(){
        $inclusive_dates = Carbon::parse($this->inclusive_date_from)->format('Y/m/d') . ' - ' . Carbon::parse($this->inclusive_date_to)->format('Y/m/d');
        return $inclusive_dates;
    }

    public static function boot()
    {
        parent::boot();
        // static::creating(function ($model) {
        //     // $latest = SalesQuotation::all()->whereRaw('Right(quoteNum,2) <> "dm"')->last();
        //     $current_payroll_period_id = PayrollPeriod::where('current_active', 1)->value('id');
        //     $model->payroll_period_id = $current_payroll_period_id;
        // });

        static::created(function ($model) {
            DB::transaction(function () use ($model) {
                $latest = DB::table('leave_requests')->lockForUpdate()->max('series');

                if (!empty($latest)) {
                    $next_series = intval(substr($latest, -6)) + 1;
                    $model->series = str_pad((string)$next_series, 6, '0', STR_PAD_LEFT);
                } else {
                    $model->series = str_pad('1', 6, '0', STR_PAD_LEFT);
                }

                $model->save();
            });
        });
    }

    public function getCreatedByNameAttribute()
    {
        return User::find($this->created_by)?->name ?? 'N/A';
    }

    public function getForEmergencyAttribute(){
        return $this->checkIfDateIsNotWithinCurrentPayroll($this->inclusive_date_from);
    }

    public function getRejectedByNameAttribute()
    {
        $rejectedById = $this->rejected_by;

        $rejectedBy = User::find($rejectedById);

        if ($rejectedBy) {
            return $rejectedBy->name;
        } else {
            return '';
        }
    }
    // Status
    // Saved - 1
    // Approved - 2
    // Confirmed - 3
    // Rejected - 4
    // Cancelled - 5
}
