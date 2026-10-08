<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory, Caching;

    protected $fillable = [
        'holiday_type_id',
        'year',
        'code',
        'name',
        'date',
        'active',
        'is_exclusive',
        'is_provincial',
    ];

    protected $appends = [
        'holiday_type_name'
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'holiday_dropdown',
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


    public function holiday_files()
    {
        return $this->hasMany(HolidayFile::class);
    }

    public function holiday_exclusive_employee_lists(){
        return $this->hasMany(HolidayExclusiveEmployeeList::class, 'holiday_id', 'id');
    }

    public function holiday_type(){
        return $this->belongsTo(HolidayType::class);
    }

    public function getHolidayTypeNameAttribute(){
        return $this->holiday_type ? $this->holiday_type->name : 'N/A';
    }
}
