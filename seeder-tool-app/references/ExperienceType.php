<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ExperienceType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->forget('experience_type');
        });

        static::updated((function ($model) {
            $model->forget('experience_type');
        }));

        static::deleted((function ($model) {
            $model->forget('experience_type');
        }));
    }
}
