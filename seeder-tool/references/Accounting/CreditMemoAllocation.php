<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\MorphMap;
use App\Traits\Caching;

class CreditMemoAllocation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;
    use SoftDeletes;
    use MorphMap;

    protected $table = 'credit_memo_allocations';

    protected $fillable = [
        'soa_id',
        'credit_memo_id',
        'allocated_amount',
        'allocate_limit',
        'allocated_by',
    ];

    public function credit_memo()
    {
        return $this->belongsTo(CreditMemo::class);
    }

    public function statement_of_account()
    {
        return $this->belongsTo(StatementOfAccount::class, 'soa_id');
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'statement_of_account_selection',
        ];

        static::creating(function ($model) use ($cache_names) {
            $model->forget($cache_names);
        });

        static::updated(function ($model) use ($cache_names) {
            $model->forget($cache_names);
        });

        static::deleted(function ($model) use ($cache_names) {
            $model->forget($cache_names);
        });
    }
}
