<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sales\PackingList;
use App\Models\Accounting\StatementOfAccount;
use App\Traits\Caching;
use OwenIt\Auditing\Contracts\Auditable;

class StatementOfAccountItem extends Model implements Auditable
{
    use SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['final_amount', 'packing_list_num'];

    protected $fillable = [
        'statement_of_account_id',
        'packing_list_id',
        'sales_date',
        'sales_total',
        'remarks',
        'bank',
        'override_amount'
    ];

    public function getFinalAmountAttribute(){
        $amount = !empty($this->override_amount) ? $this->override_amount : $this->sales_total;

        return $amount;
    }

    public function packing_list()
    {
        return $this->belongsTo(PackingList::class);
    }

    public function statement_of_account()
    {
        return $this->belongsTo(StatementOfAccount::class);
    }

    public function getPackingListNumAttribute(){
        $pl_num = !empty($this->packing_list) ? $this->packing_list->packing_list_num : '';
        unset($this->packing_list);
        return $pl_num;
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'statement_of_account_selection',
        ];
        static::creating(function ($model) use($cache_names) {
            $model->forget($cache_names);
        });

        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));

        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }
}
