<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ScDisposal extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'container_id',
        'user_id',
        'status',
        'approved_date',
        'approved_by',
        'disposal_src_wh_id',
        'disposal_dest_wh_id',
        'remarks',
        'deleted_at',
        'cm_number',
        'sc_baklas_id',
        'sc_location_id',
        'type',
        'for_transfer',
    ];

    protected $appends = ['status_text'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $latest = ScDisposal::select('disposal_number')->whereRaw('length(disposal_number)=6')->max('disposal_number');

            if(!empty($latest)){
                $next_disposal_num = ($latest != null) ? intval($latest) + 1: 1;
                $model->disposal_number = str_pad((string)$next_disposal_num, 6, '0', STR_PAD_LEFT);
            }else{
                $model->disposal_number = str_pad('1', 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function disposal_items()
    {
        return $this->hasMany(ScDisposalItem::class);
    }

    public function getStatusTextAttribute() {
        if($this->status==0){
            return 'saved';
        }elseif($this->status==1){
            return 'approved';
        }elseif($this->status==2){
            return 'disposed';
        }elseif($this->status==3){
            return 'recovered';
        }elseif($this->status==4){
            return 'pending';
        }elseif($this->status==5){
            return 'canceled';
        }elseif($this->status==6){
            return 'for-approval';
        }elseif($this->status==7){
            return 'consumed';
        }elseif($this->status==8){
            return 'for-confirmation';
        }
    }
}
