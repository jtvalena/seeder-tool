<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\EmployeeOvertimeRecordItems;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeOvertimeRecord extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'employee_overtime_records';

    protected $fillable = [
        'id',
        'department_id',
        'ot_date',
        'payroll_period_id',
        // 'payroll_cut_off_id',
        'status',
        'remarks',
        'created_by',
        'deleted_at',
        'created_at',
        'updated_at',
        'series'
    ];

    protected $appends = [
        'has_overrided',
        'pending_count',
        'rejected_count',
        'approved_count',
        'confirmed_count',
        'employee_overtime_record_item_ids',
    ];


    public function employee_overtime_record_items()
    {
        return $this->hasMany(EmployeeOvertimeRecordItems::class, 'employee_overtime_record_id');
    }

    public function getEmployeeOvertimeRecordItemIdsAttribute(){
        return EmployeeOvertimeRecordItems::where('employee_overtime_record_id', $this->id)->get()->map(function ($item) {
            $item = $item->only(['id', 'status','employee_overtime_record_id', 'payroll_period_id', 'series', 'for_emergency', 'employee_id']);
            $item['reject_reason'] = '';
            return $item;
        });

    }

    public function getHasOverridedAttribute(){
        $count = EmployeeOvertimeRecordItems::where('employee_overtime_record_id', $this->id)
            ->where('overrided', 1)
            ->count();
        return $count;
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
                $latest = DB::table('employee_overtime_records')->lockForUpdate()->max('series');

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

    public function getPendingCountAttribute(){
        $overtime_record_ids = EmployeeOvertimeRecord::where('ot_date', $this->ot_date)
            ->where('department_id', $this->department_id)
            ->pluck('id');
        if(!empty($overtime_record_ids)){
            return EmployeeOvertimeRecordItems::whereIn('employee_overtime_record_id', $overtime_record_ids)->where('status', 1)->count();
        }else{
            return 0;
        }
        // return $this->employee_overtime_record_items()->where('status', 1)->count();
    }

    public function getOvertimeCountsAttribute()
    {
        $overtime_record_ids = EmployeeOvertimeRecord::where('ot_date', $this->ot_date)
            ->where('department_id', $this->department_id)
            ->pluck('id');

        if ($overtime_record_ids->isEmpty()) {
            return [
                'rejected' => 0,
                'approved' => 0,
                'confirmed' => 0,
            ];
        }

        $counts = EmployeeOvertimeRecordItems::whereIn('employee_overtime_record_id', $overtime_record_ids)
            ->selectRaw('
                SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as rejected_count,
                SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as approved_count,
                SUM(CASE WHEN status = 4 THEN 1 ELSE 0 END) as confirmed_count
            ')
            ->first();

        return [
            'rejected' => $counts->rejected_count ?? 0,
            'approved' => $counts->approved_count ?? 0,
            'confirmed' => $counts->confirmed_count ?? 0,
        ];
    }

    public function getRejectedCountAttribute()
    {
        return $this->overtime_counts['rejected'];
    }

    public function getApprovedCountAttribute()
    {
        return $this->overtime_counts['approved'];
    }

    public function getConfirmedCountAttribute()
    {
        return $this->overtime_counts['confirmed'];
    }


    // Status
    // Saved - 1
    // Partial - 2
    // Completed - 3

}
