<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Service\ScBaklasItem;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScBaklas extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'baklas_number'
    ];

    public function sc_baklas_items()
    {
        return $this->hasMany(ScBaklasItem::class, 'sc_baklas_id');
    }

    public function warehouse_item_stocks() {
        return $this->belongsTo(WarehouseItemStocks::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = ScBaklas::select('baklas_number')->whereRaw('length(baklas_number)=6')->max('baklas_number');
            if (!empty($latest)) {
                $next_baklas_num = ($latest != null) ? intval($latest) + 1 : 1;
                $model->baklas_number = str_pad((string)$next_baklas_num, 6, '0', STR_PAD_LEFT);
                $model->item_status = 'defective';
            } else {
                $model->baklas_number = str_pad('1', 6, '0', STR_PAD_LEFT);
                $model->item_status = 'defective';
            }
        });
    }
}
