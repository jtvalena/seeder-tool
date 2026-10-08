<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class RequiredDocument extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->forget('manpower_request_reason_type_dropdown');
        });

        static::updated((function ($model) {
            $model->forget('manpower_request_reason_type_dropdown');
        }));

        static::deleted((function ($model) {
            $model->forget('manpower_request_reason_type_dropdown');
        }));
    }
}
