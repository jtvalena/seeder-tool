<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimekeepingRecord extends Model implements Auditable
{
    use HasFactory, Caching;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        'timekeeping_num',
        'payroll_period_id',
        'generated_by',
        'approved_by',
        'approved_date',
        'confirmed_by',
        'confirmed_date',
        'status',
        'remarks',
        'timekeeping_period_start',
        'timekeeping_period_end',
    ];

    protected $appends = [
        'approved_by_name',
        'confirmed_by_name',
        'timekeeping_record_detail_ids',
    ] ;

    public function timekeeping_record_details(){
        return $this->hasMany(TimekeepingRecordDetail::class, 'timekeeping_record_id');
    }

    public function getApprovedByNameAttribute(){

        $approvedBy = $this->approved_by;

        $approved_by = User::find($approvedBy);

        if ($approved_by) {
            return $approved_by->name;
        } else {
            return '';
        }
    }

    public function getConfirmedByNameAttribute(){

        $confirmedBy = $this->confirmed_by;

        $confirmed_by = User::find($confirmedBy);

        if ($confirmed_by) {
            return $confirmed_by->name;
        } else {
            return '';
        }
    }

    public function getTimekeepingRecordDetailIdsAttribute(){
        // $ids = TimekeepingRecordDetail::getQuery()->where('timekeeping_record_id', $this->id)->pluck('id')->toArray();
        // return $ids;
        return [];
    }

    public function payroll_period(){
        return $this->belongsTo(PayrollPeriod::class);
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'timekeeping_periods',
        ];
        // static::creating(function ($model) {
        //     $current_payroll_period_id = PayrollPeriod::where('current_active', 1)->value('id');
        //     $model->payroll_period_id = $current_payroll_period_id;
        // });
        static::created((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }

    // Status
    // 1 - Saved
    // 2 - Approved
    // 3 - Confirmed
}
