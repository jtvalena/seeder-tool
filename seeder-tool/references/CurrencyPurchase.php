<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CurrencyPurchase extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'doc_num',
        'doc_date',
        'status',
        'currency_id_local',
        'currency_id_foreign',
        'exchange_rate',
        'source_bank_id_local',
        'destination_bank_id_local',
        'bank_id_foreign',
        'remarks',
        'foreign_amount',
        'local_amount',
        'paid',
        'system_id',
        'payment_for',
        'vendor_id',
    ];

    protected $appends = [
        'status_text',
    ];

    public function currency_local()
    {
        return $this->belongsTo(Currency::class, 'currency_id_local');
    }

    public function currency_foreign()
    {
        return $this->belongsTo(Currency::class, 'currency_id_foreign');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = CurrencyPurchase::select('doc_num')->whereRaw('length(doc_num)=6')->max('doc_num');
            if(!empty($latest)){
                $next_num = ($latest != null) ? intval($latest) + 1: 1;
                $model->doc_num = str_pad((string)$next_num, 6, '0', STR_PAD_LEFT);
            }else{
                $model->doc_num = str_pad('1', 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getStatusTextAttribute()
    {
        if ($this->status == 0) {
            return 'pending';
        } else if ($this->status == 1) {
            return 'for approval';
        } else if ($this->status == 2) {
            return 'approved';
        } else if ($this->status == 3) {
            return 'cancelled';
        }
    }

    public function cancelled_record()
    {
        return $this->hasOne(CancelledCurrencyPurchase::class);
    }
}
