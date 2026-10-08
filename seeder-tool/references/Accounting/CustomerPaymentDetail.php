<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\MorphMap;
use App\Models\Accounting\Bank;
use App\Models\Accounting\PaymentType;
use App\Models\Accounting\StatementOfAccount;
use App\Models\Accounting\CustomerPaymentAllocation;
use App\Models\MasterData\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Caching;

class CustomerPaymentDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;
    use SoftDeletes;
    use MorphMap;

    protected $appends = ['bank_name', 'payment_type_name','company_name'];

    protected $fillable = [
        'reference_number',
        'is_mother_customer',
    ];

    public function statement_of_account()
    {
        return $this->belongsTo(StatementOfAccount::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function customer_payment_allocations()
    {
        return $this->hasMany(CustomerPaymentAllocation::class, 'customer_payment_detail_id');
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'statement_of_account_selection',
        ];

        static::creating(function ($model) use($cache_names) {
            $latest = CustomerPaymentDetail::select('reference_number')->whereRaw('length(reference_number)=7')->max('reference_number');
            if (!empty($latest)) {
                $next_reference_number = ($latest != null) ? intval($latest) + 1 : 1;
                $model->reference_number = str_pad((string)$next_reference_number, 7, '0', STR_PAD_LEFT);
            } else {
                $model->reference_number = str_pad('1', 7, '0', STR_PAD_LEFT);
            }
            $model->forget($cache_names);
        });

        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));

        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }

    public function getBankNameAttribute() {
        return !!Bank::find($this->bank_id) ? Bank::find($this->bank_id)->name : '';
    }

    public function getPaymentTypeNameAttribute() {
        return !!PaymentType::find($this->payment_type_id) ? PaymentType::find($this->payment_type_id)->name : '';
    }

    public function getCompanyNameAttribute() {
        return !!Customer::find($this->customer_id) ? Customer::find($this->customer_id)->company_name : '';
    }

}
