<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Liquidation extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'liquidation_number',
        'taf_number',
        'name',
        'date_received',
        'date_liquidated',
        'department_id',
        'transact_id',
        'transact_type_id',
        'total_amount',
        'designation',
        'status',
        'cash_denomination',
        'dept_approved_by',
        'dept_approved_date',
        'acct_approved_by',
        'acct_approved_date',
    ];

    public function liquidation_items()
    {
        return $this->hasMany(LiquidationItem::class, 'liquidation_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $lastTransactionNumber = static::withTrashed()->max('liquidation_number');
            $model->liquidation_number = str_pad($lastTransactionNumber ? ++$lastTransactionNumber : '000001', 6, '0', STR_PAD_LEFT);
            $model->status = 1;
        });
    }
}
