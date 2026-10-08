<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ManpowerRequestSpecialSkill extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->forget('manpower_request_special_skill');
        });

        static::updated((function ($model) {
            $model->forget('manpower_request_special_skill');
        }));

        static::deleted((function ($model) {
            $model->forget('manpower_request_special_skill');
        }));
    }
}
