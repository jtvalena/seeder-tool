<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CreditMemoClassification extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable, Caching;

    protected $appends = [
        'origin_name'
    ];

    protected $fillable = [
        'code',
        'name',
        'deduct_from_sales',
        'origin',
        'claims_default',
        'company_id',
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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->forget('credit_memo_classification_dropdown');
        });

        static::updated((function ($model) {
            $model->forget('credit_memo_classification_dropdown');
        }));

        static::deleted((function ($model) {
            $model->forget('credit_memo_classification_dropdown');
        }));
    }

}
