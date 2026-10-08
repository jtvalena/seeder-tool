<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class AdminStoreOrder extends Model implements Auditable
{
    use HasFactory,SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'store_order_num',
        'location_id',
        'shipping_via_id',
        'vendor_id',
        'vendor_payment_item_id',
        'vendor_address_id',
        'total_amount',
        'remarks',
        'status',
        'type',
        'prepared_by',
        'checked_by',
        'noted_by',
        'company_id',
        'store_branch_id',
        'expected_date',
        'number_of_days',
        'deleted_at',
        'comments',
        'for_payment',
        'payment_method',
        'amount_to_pay',
        'total_paid_amount',
        'is_overdue',
        'rush',
        'updated_by',
        'payment_type_id',
        'contact_person',
        'contact_number',
        'customer_request',
        'customer_id',
        'billable',
        'requestor',
        'request_id',
        'confirmed_by',
        'approve_admin_by',
        'exec_approved_by',
        'confirmed_date',
        'admin_approved_date',
        'exec_approved_date',
        'admin_fas_basket_id',
        'outbound_status',
    ];

    public function location(){
        return $this->belongsTo(AdminLocation::class,'location_id','id');
    }
    public function User(){
        return $this->belongsTo(User::class,'prepared_by','id');
    }

    public static function boot()
    {
        parent::boot();

        $cache_names = [
            'store_order_num_selection',
            'admin_store_order_selection',
            'puchase_order_num',
            'admin_store_order',
            'store_order_number_asset_selection'
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

    public function has_request_items(){
        return $this->hasMany(AdminRequestTransactionItem::class,'transaction_id','value');
    }

    public function admin_store_order_items(){
        return $this->hasMany(AdminStoreOrderItem::class,'admin_store_order_id');
    }

}
