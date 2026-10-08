<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerCode extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    // customer_code_selection
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->forget('customer_code_selection');
        });

        static::updated((function ($model) {
            $model->forget('customer_code_selection');
        }));

        static::deleted((function ($model) {
            $model->forget('customer_code_selection');
        }));
    }
}
