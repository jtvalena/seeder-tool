<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;


class AccountingFundamental extends Model implements Auditable
{
    use AuditingAuditable, SoftDeletes, Caching;

    protected $fillable = [
        'code',
        'name',
    ];

    public function children()
    {
        return $this->hasMany(ChartOfAccounts::class, 'fundamental_id');
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'fundamentals_dropdown',
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
