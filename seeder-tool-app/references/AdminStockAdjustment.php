<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminStockAdjustment extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'admin_stock_adjustments';

    protected $fillable = [
        'reference_num',
        'admin_warehouse_id',
        'remarks',
        'requestor_id',
        'requestor_name',
        'created_by_id',
        'created_by_name',
        'status',
        'processed_by',
        'processed_date',
        'adjust_stock',
        'adjust_movement'
    ];

    public function stock_adjustment_items()
    {
        return $this->hasMany(AdminStockAdjustmentItem::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = AdminStockAdjustment::select('reference_num')->whereRaw('length(reference_num)=5')->max('reference_num');
            if (!empty($latest)) {
                $next_reference_num = ($latest != null) ? intval($latest) + 1 : 1;
                $model->reference_num = str_pad((string)$next_reference_num, 5, '0', STR_PAD_LEFT);
            } else {
                $model->reference_num = str_pad("1", 5, 0, STR_PAD_LEFT);
            }
        });
    }
}
