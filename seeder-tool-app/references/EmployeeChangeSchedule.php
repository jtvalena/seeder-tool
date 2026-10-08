<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeChangeSchedule extends Model
{
    use HasFactory;

    protected $table = 'employee_change_schedules';

    protected $fillable = [
        'series',
        'employee_id',
        'date_from',
        'date_to',
        'status',
        'created_by',
        'approved_by',
        'approved_date',
        'confirmed_by',
        'confirmed_date',
        'remarks',
    ];

    protected $appends = ['status_name'];

    public function employee_data()
    {
        return $this->belongsTo(EmployeeData::class, 'employee_id');
    }

    public function items()
    {
        return $this->hasMany(EmployeeChangeScheduleItem::class, 'employee_change_schedule_id');
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            DB::transaction(function () use ($model) {
                $latest = DB::table('employee_change_schedules')->lockForUpdate()->max('series');
                if (!empty($latest)) {
                    $next = intval(substr($latest, -6)) + 1;
                    $model->series = 'ECS-' . str_pad((string)$next, 6, '0', STR_PAD_LEFT);
                } else {
                    $model->series = 'ECS-000001';
                }
                $model->save();
            });
        });
    }

    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case 1: return 'Pending';
            case 2: return 'Approved';
            case 3: return 'Confirmed';
            default: return 'Unknown';
        }
    }
}
