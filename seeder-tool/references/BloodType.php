<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BloodType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    protected $fillable = [
        'code',
        'name',
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'blood_type_dropdown',
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
