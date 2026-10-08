<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeScheduleType extends Model
{
    use HasFactory, Caching;

    protected $fillable = [
        'name',
        'code',
        'special',
        'flexi',
        'active',
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'employee_schedule_type_dropdown',
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
