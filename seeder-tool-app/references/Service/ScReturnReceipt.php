<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Service\ScReturnReceiptItem;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScReturnReceipt extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'series',
        'sc_repair_form_id',
        'total_no_of_items',
        'status',
        'created_by',
        'processed_by',
        'received_by'
    ];

    public function sc_return_receipt_items()
    {
        return $this->hasMany(ScReturnReceiptItem::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $latest = ScReturnReceipt::select('series')->whereRaw('length(series)=5')->max('series');

            if(!empty($latest)){
                $next_series = ($latest != null) ? intval($latest) + 1: 1;
                $model->series = str_pad((string)$next_series, 5, '0', STR_PAD_LEFT);
            }else{
                $model->series = str_pad('1', 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
