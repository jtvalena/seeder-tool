<?php

namespace App\Models\Accounting;

use App\Traits\Caching;
use App\Traits\MorphMap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class DebitMemoAllocation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;
    use MorphMap;
    use SoftDeletes;

    protected $table = 'debit_memo_allocations';

    protected $fillable = [
        'soa_id',
        'debit_memo_id',
        'allocated_amount',
        'allocate_limit',
        'allocated_by',
    ];

    public function debit_memo()
    {
        return $this->belongsTo(DebitMemo::class);
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
