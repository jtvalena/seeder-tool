<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeAttendanceJustification extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'created_by',
        'date_of_filing',
        'confirmed_by',
        'confirmed_date',
        'status',
        'series',
        'payroll_period_id',
    ];

    protected $appends = [
        'employee_name',
        'pending_count',
        'approved_count',
        'rejected_count',
        'ob_dates'
    ];

    public function getEmployeeNameAttribute(){
        $employeeData = EmployeeData::find($this->employee_id)->setAppends([
            'employee_full_name',
        ]);

        $middle_name = is_null($employeeData->middle_name) ? '' : substr($employeeData->middle_name, 0, 1);
        $name = $employeeData->last_name. ", " . $employeeData->first_name . " ".  $middle_name . ".";
        return $name;
    }

    public function getPendingCountAttribute(){
        $count = EmployeeAttendanceJustificationItem::where('employee_attendance_justification_id', $this->id)
            ->where('status', 1)
            ->count();

        return $count;
    }

    public function getApprovedCountAttribute(){
        $count = EmployeeAttendanceJustificationItem::where('employee_attendance_justification_id', $this->id)
            ->whereIn('status', [3, 4])
            ->count();

        return $count;
    }

    public function getRejectedCountAttribute(){
        $count = EmployeeAttendanceJustificationItem::where('employee_attendance_justification_id', $this->id)
            ->where('status', 2)
            ->count();

        return $count;
    }

    public function getObDatesAttribute(){
        $obDates = EmployeeAttendanceJustificationItem::where('employee_attendance_justification_id', $this->id)
            ->pluck('eaj_date')
            ->toArray();
        return $obDates;
    }

    public function employee_attendance_justification_items(){
        return $this->hasMany(EmployeeAttendanceJustificationItem::class, 'employee_attendance_justification_id');
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
                $latest = DB::table('employee_attendance_justifications')->lockForUpdate()->max('series');

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

    // Status
    // 1 - Saved
    // 2 - Confirmed
    // 3 - Partial
    // 4 - Approved
}
