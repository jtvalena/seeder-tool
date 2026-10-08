<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\EmployeeOBRecordItems;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeOBRecord extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'employee_ob_records';

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
        $count = EmployeeOBRecordItems::where('ob_record_id', $this->id)
            ->whereNull('approved_by')
            ->whereNull('rejected_by')
            ->count();

        return $count;
    }

    public function getApprovedCountAttribute(){
        $count = EmployeeOBRecordItems::where('ob_record_id', $this->id)
            ->whereNotNull('approved_by')
            ->whereNull('rejected_by')
            ->count();

        return $count;
    }

    public function getRejectedCountAttribute(){
        $count = EmployeeOBRecordItems::where('ob_record_id', $this->id)
            ->whereNull('approved_by')
            ->whereNotNull('rejected_by')
            ->count();

        return $count;
    }

    public function getObDatesAttribute(){
        $obDates = EmployeeOBRecordItems::where('ob_record_id', $this->id)
            ->pluck('ob_date')
            ->toArray();
        return $obDates;
    }

    public function employee_ob_record_items(){
        return $this->hasMany(EmployeeOBRecordItems::class, 'ob_record_id');
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
                $latest = DB::table('employee_ob_records')->lockForUpdate()->max('series');

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
