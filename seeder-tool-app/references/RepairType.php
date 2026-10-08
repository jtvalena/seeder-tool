<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class RepairType extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable, Caching;

    protected $fillable = ['code','name','type','others'];
    protected $table = 'repair_type';

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'repair_type_selection',
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
