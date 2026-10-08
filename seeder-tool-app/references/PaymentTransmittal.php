<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransmittal extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['status_text'];

    protected $guarded = [];

    public static function boot(){
        parent::boot();

        static::creating(function ($model) {
            $latest = PaymentTransmittal::orderBy('id','desc')->first();

            if (!empty($latest)) {
                $latest = (int)$latest->payment_transmittal_num;
                $next_pt_num = ($latest != null) ? intval($latest) + 1 : 1;
                $model->payment_transmittal_num = str_pad((string)$next_pt_num, 5, '0', STR_PAD_LEFT);
            } else {
                $model->payment_transmittal_num = str_pad('1', 5, '0', STR_PAD_LEFT);
            }

        });
    }

    public function getStatusTextAttribute() {
        if($this->status==0){
            return 'saved';
        }elseif($this->status==1){
            return 'transmitted';
        }
    }

}
