<?php

namespace App\Models;

use App\Models\MasterData\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Str;

class ClaimsClaim extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'claims_shop_id',
        'claims_template_id',
        'prepared_by',
        'claim_num',
        'doc_date',
        'remarks',
        'status',
        'subsidy_amount',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'selected_year',
        'selected_month',
        'credit_memo_id',
        'printed',
        'prepared_by',
    ];

    protected $appends = [
        'soa_month',
        'status_text',
    ];

    public function details()
    {
        return $this->hasMany(ClaimsClaimItem::class);
    }

    public function discounts()
    {
        return $this->hasMany(ClaimsClaimDiscount::class);
    }

    public function customer()
    {
        return $this->hasOneThrough(Customer::class, ClaimsShop::class, 'id', 'id', 'claims_shop_id', 'customer_id');
    }

    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = ClaimsClaim::select('claim_num')->whereRaw('length(claim_num)=6')->max('claim_num');
            if(!empty($latest)){
                $next_num = ($latest != null) ? intval($latest) + 1: 1;
                $model->claim_num = str_pad((string)$next_num, 6, '0', STR_PAD_LEFT);
            }else{
                $model->claim_num = str_pad('1', 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getSoaMonthAttribute()
    {
        return (!!$this->selected_year && !!$this->selected_month) ? $this->selected_year . '-' . Str::padLeft($this->selected_month, 2, '0') : 'N/A';
    }

    public function getStatusTextAttribute()
    {
        if ($this->status == 0) {
            return 'pending';
        } else if ($this->status == 1) {
            return 'for approval';
        } else if ($this->status == 3) {
            return 'approved';
        } else if ($this->status == 5) {
            return 'rejected';
        } else if ($this->status == 6) {
            return 'canceled';
        }
    }
}
