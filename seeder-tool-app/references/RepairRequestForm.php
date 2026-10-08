<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class RepairRequestForm extends Model implements Auditable
{
    use HasFactory,SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    use HasFactory;

    public static function boot()
    {
        parent::boot();

        $cache_names = [
            'all_rrf_num',
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
