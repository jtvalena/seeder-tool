<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeReligion extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    protected $fillable = [
        'code',
        'name',
        'active',
    ];

    public static function boot()
    {
        parent::boot();

        static::created((function ($model) {
            $model->forget('employee_religion_dropdown');
        }));
        static::updated((function ($model) {
            $model->forget('employee_religion_dropdown');
        }));
        static::deleted((function ($model) {
            $model->forget('employee_religion_dropdown');
        }));
    }
}
