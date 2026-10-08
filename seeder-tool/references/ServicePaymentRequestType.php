<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ServicePaymentRequestType extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use Caching;

    protected $fillable = [
        'name',
        'vendor_id',
        'company_id',
        'store_branches_id',
        'bill_due',
        'terms',
        'total_amount',
        'remarks',
        'active',
        'payment_type_id'
    ];

    public function service_payment_request_type_items()
    {
        return $this->hasMany(ServicePaymentRequestTypeItem::class, 'service_payment_request_type_id');
    }

    public function service_payment_request_departments()
    {
        return $this->hasMany(ServicePaymentRequestDepartment::class, 'service_payment_request_type_id');
    }
    
    // public static function boot()
    // {
    //     parent::boot();
    //     $cache_names = [
    //         'spr_type_dropdown',
    //     ];

    //     static::creating(function ($model) use($cache_names) {
    //         $model->forget($cache_names);
    //     });

    //     static::updated(function ($model) use($cache_names) {
    //         $model->forget($cache_names);
    //     });

    //     static::deleted(function ($model) use($cache_names) {
    //         $model->forget($cache_names);
    //     });
    // }
}
