<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ItemQaChecked extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    protected $guarded = [];
    // protected $fillables = ['item_id', 'sc_repair_form_id', 'check_list_id', 'passed'];
    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'checklist',
            'item_qa_checked',
            'specific_list_selection'
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
