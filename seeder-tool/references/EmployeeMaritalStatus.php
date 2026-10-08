<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeMaritalStatus extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    public static function boot()
    {
        parent::boot();

        static::created((function ($model) {
            $model->forget('employee_marital_status');
        }));
        static::updated((function ($model) {
            $model->forget('employee_marital_status');
        }));
        static::deleted((function ($model) {
            $model->forget('employee_marital_status');
        }));
    }
}
