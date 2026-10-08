<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Statuses extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    protected $fillable = [
        'code',
        'name',
        'department_id',
        'promotion',
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'status_hr'
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
