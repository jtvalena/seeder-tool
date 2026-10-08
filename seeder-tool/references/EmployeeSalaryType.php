<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeSalaryType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->forget('salary_type_selection');
        });

        static::updated((function ($model) {
            $model->forget('salary_type_selection');
        }));

        static::deleted((function ($model) {
            $model->forget('salary_type_selection');
        }));
    }
}
