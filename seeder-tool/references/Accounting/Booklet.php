<?php

namespace App\Models\Accounting;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Booklet extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes, Caching;
    protected $fillable = [
        'bank_id',
        'user_id',
        'booklet_number',
        'booklet_cheque_number_start',
        'booklet_cheque_number_end',
        'booklet_cheque_number_last_used',
        'selection_valid_until',
        'offet_x',
        'offet_y',
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'booklet_selection',
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
