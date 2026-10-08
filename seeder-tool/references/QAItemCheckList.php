<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class QAItemCheckList extends Model implements Auditable
{
    use SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'q_a_item_check_lists';

    protected $fillable = [
        'id',
        'item_id',
        'check_list_id',
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'specific_list_selection',
            'checklist'
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
