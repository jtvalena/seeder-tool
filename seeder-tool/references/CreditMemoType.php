<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\CreditMemoClassification;

class CreditMemoType extends Model implements Auditable
{
    use HasApiTokens, Notifiable, Caching;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = [ 'origin_name', 'status_name', 'credit_memo_classification_name'];

    protected $fillable = [
        'code',
        'name',
        'origin',
        'status',
        'company_id',
        'linked_type',
        'service',
        'debit_coa_id',
        'credit_coa_id',
        'claims_default',
        'credit_memo_classification_id',
        'active',
    ];

    public function getOriginNameAttribute()
    {
        /*
            1 = 'LOCAL'
            2 = 'INTERNATIONAL'
        */

        if($this->origin==1){
            return 'LOCAL';
        }elseif($this->origin==2){
            return 'INTERNATIONAL';
        }
    }

    public function getStatusNameAttribute()
    {
        /*
            1 = 'WITH QTY AND PRICE'
            2 = 'WITH PRICE ONLY'
            3 = 'GENERIC'
        */

        if($this->status==1){
            return 'WITH QTY AND PRICE';
        }elseif($this->status==2){
            return 'WITH PRICE ONLY';
        }elseif($this->status==3){
            return 'GENERIC';
        }
    }

    public function classification()
    {
        return $this->belongsTo(CreditMemoClassification::class, 'credit_memo_classification_id');
    }

    public function getCreditMemoClassificationNameAttribute()
    {
        return $this->classification->name ?? '';
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'customer_with_cm_selection',
            'credit_memo_selection'
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
