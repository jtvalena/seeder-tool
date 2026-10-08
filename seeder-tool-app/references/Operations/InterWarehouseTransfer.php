<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class InterWarehouseTransfer extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'inter_warehouse_transfers';

    protected $fillable = [
        'iw_number',
        'source_warehouse_id',
        'destination_warehouse_id',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
        'confirmed_by',
        'confirmed_at',
        'canceled_by',
        'canceled_at',
        'remarks',
        'service',
        'outbound_printed',
        'received_printed',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $latest = InterWarehouseTransfer::select('iw_number')->whereRaw('length(iw_number)=6')->max('iw_number');

            if(!empty($latest)){
                $next_number = ($latest != null) ? intval($latest) + 1: 1;
                $model->iw_number = str_pad((string)$next_number, 6, '0', STR_PAD_LEFT);
            }else{
                $model->iw_number = str_pad('1', 6, '0', STR_PAD_LEFT);
            }
        });
    }
}

