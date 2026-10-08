<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ScRecovery extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function sc_recovery_items()
    {
        return $this->hasMany(ScRecoveryItem::class, 'sc_recovery_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $latest = ScRecovery::select('series')->whereRaw('length(series)=6')->max('series');

            if(!empty($latest)){
                $next_series = ($latest != null) ? intval($latest) + 1: 1;
                $model->series = str_pad((string)$next_series, 6, '0', STR_PAD_LEFT);
            }else{
                $model->series = str_pad('1', 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getStatusTextAttribute()
    {
        if($this->status==0){
            return 'saved';
        }else if($this->status==1){
            return 'approved';
        }else if($this->status==2){
            return 'confirmed';
        }else if($this->status==3){
            return 'recovered';
        }else if($this->status==4){
            return 'canceled';
        }
    }
}
