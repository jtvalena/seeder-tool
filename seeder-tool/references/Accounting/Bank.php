<?php

namespace App\Models\Accounting;

use App\Models\BankPaymentType;
use App\Models\BankType;
use App\Models\BankUserAccess;
use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Bank extends Model implements Auditable
{
    use SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $fillable =
    [
        'bank_master_id',
        'local',
        'currency_id',
        'code',
        'name',
        'closed_account',
        'owner_name',
        'email',
        'contact_number',
        'current_balance',
        'available_balance',
        'bank_balance',
        'base_fee_usd',
    ];

    public function bank_payment_types()
    {
        return $this->hasMany(BankPaymentType::class);
    }
    public function bank_user_accesses()
    {
        return $this->hasMany(BankUserAccess::class);
    }
    public function bank_types()
    {
        return $this->hasMany(BankType::class);
    }
    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'banks_selection',
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
