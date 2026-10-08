<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeContractType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    public static function boot()
    {
        parent::boot();

        static::created((function ($model) {
            $model->forget('employee_contract_type');
        }));
        static::updated((function ($model) {
            $model->forget('employee_contract_type');
        }));
        static::deleted((function ($model) {
            $model->forget('employee_contract_type');
        }));
    }
}
