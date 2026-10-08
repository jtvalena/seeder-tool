<?php

namespace App\Models;

use App\Traits\Caching;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkScheduleTemplate extends Model implements Auditable
{
    use HasFactory, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'schedule_type_id',
        'days_to_work',
        'hours_to_work_week',
        'break_duration',
        'user_id',
        'date_from',
        'date_to',
        'flexible_breaktime',
    ];

    protected $appends = [
        'schedule_type_name'
    ];

    public function getScheduleTypeNameAttribute(){
        $schedule_type_name = EmployeeScheduleType::where('id', $this->schedule_type_id)->value('name');

        return $schedule_type_name ?? '';
    }

    public function work_schedule_template_details(){
        return $this->hasMany(WorkScheduleTemplateDetails::class,'work_schedule_template_id')->orderByRaw("FIELD(day, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')");
    }

    public function work_schedule_break_durations(){
        return $this->hasMany(WorkScheduleBreakDuration::class, 'work_schedule_template_id');
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'work_schedule_templates',
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
}
