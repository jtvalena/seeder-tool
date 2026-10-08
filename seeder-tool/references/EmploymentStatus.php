<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmploymentStatus extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    protected $fillable = [
        'code',
        'name',
        'active',
        'default',
        'promotion',
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'employment_status_selection',
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
