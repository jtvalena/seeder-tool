<?php

namespace App\Models;

use App\Models\Service\ScBaklas;
// use App\Models\Service\ScRepairForm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PartsTransfer extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'source_warehouse_id',
        'destination_warehouse_id',
        'status',
        'total_quantity',
        'remarks',
        'user_id',
        'sc_repair_form_id',
        'sc_parts_order_id',
        'sc_baklas_id',
        'return_reason',
        'sc_disposal_id',
        'sc_recovery_id',
    ];

    protected $appends = [
        'baklas_number'
    ];

    public static function boot(){
        parent::boot();

        static::creating(function ($model) {
            $latest = PartsTransfer::orderBy('id','desc')->first();

            if (!empty($latest)) {
                $latest = (int)$latest->series;
                $next_rf_num = ($latest != null) ? intval($latest) + 1 : 1;
                $model->series = str_pad((string)$next_rf_num, 5, '0', STR_PAD_LEFT);
            } else {
                $model->series = str_pad('1', 5, '0', STR_PAD_LEFT);
            }

            $model->status_date = now()->format('Y-m-d');
        });
    }

    // public function getRfNumberAttribute()
    // {
    //     if($this->sc_repair_form_id){
    //         return ScRepairForm::find($this->sc_repair_form_id)->value('rf_number');
    //     }else{
    //         return '';
    //     }
    // }

    public function getBaklasNumberAttribute()
    {
        if ($this->sc_baklas_id)
        {
            return ScBaklas::find($this->sc_baklas_id)->baklas_number ?? '';
        } else {
            return '';
        }
    }
}
