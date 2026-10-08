<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PayrollPeriod extends Model implements Auditable
{
    use HasFactory, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'payroll_num',
        'payroll_period_info_id',
        'name',
        'date_from',
        'date_to',
        'status_id',
        'salary_type_id',
        'approved_by',
        'approved_date',
        'confirmed_by',
        'confirmed_date',
        'for_final_pay',
        'cut_off_text',
        'printed',
        'release_date',
        'thirteenth',
        'payroll_year',
        'payroll_month',
        'current_active',
        'parent_payroll_period_id',
        'remarks',
        'emergency',
        'emergency_name',
        'initialized',
        'for_adjustment',
    ];

    protected $appends = [
        'status_text',
        'no_of_holiday',
        'has_current_active',
    ];

    public function payroll_period_details(){
        return $this->hasMany(PayrollPeriodDetail::class, 'payroll_period_id', 'id');
    }

    public function payroll_period_info(){
        return $this->belongsTo(PayrollPeriodInfo::class);
    }

    public function emergency_payroll_periods()
    {
        return $this->hasMany(PayrollPeriod::class, 'parent_payroll_period_id', 'id');
    }

    public function emergency_payroll_period_details()
    {
        return $this->hasManyThrough(
            PayrollPeriodDetail::class,       // Final target
            PayrollPeriod::class,              // Intermediate model
            'parent_payroll_period_id',                    // FK on intermediate (payroll_periods)
            'payroll_period_id',                           // FK on final (payroll_period_details)
            'id',                                          // Local key on this model
            'id'                                           // Local key on intermediate
        );
    }

    public static function boot()
    {
        parent::boot();

        $cache_names = [
            'payroll_period_selection',
            'payroll_period_info_selection'
        ];

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

    public function getNoOfHolidayAttribute(){
        $holidays = Holiday::whereBetween('date', [$this->date_from, $this->date_to])->count();
        return $holidays;
    }

    public function getHasCurrentActiveAttribute(){
        return PayrollPeriod::where('current_active', 1)->exists();
    }

    public function getStatusTextAttribute()
    {
        if ($this->status_id == 1) {
            return 'saved';
        } else if ($this->status_id == 2) {
            return 'for timekeeping processing';
        } else if ($this->status_id == 3) {
            return 'for timekeeping approval';
        } else if ($this->status_id == 4) {
            return 'for timekeeping confirmation';
        } else if ($this->status_id == 5) {
            return 'for payroll processing';
        } else if ($this->status_id == 6) {
            return 'for payroll approval';
        } else if ($this->status_id == 7) {
            return 'for payroll confirmation';
        } else if ($this->status_id == 8) {
            return 'completed';
        }
    }
}
