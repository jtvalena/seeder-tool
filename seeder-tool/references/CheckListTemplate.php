<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CheckListTemplate extends Model implements Auditable
{
    use SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'template_id',
        'check_list_id',
        'deleted_at'
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'templates',
            'checklist_template'
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
