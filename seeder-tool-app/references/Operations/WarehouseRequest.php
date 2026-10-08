<?php
namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Operations\Warehouse;
use App\Models\User;

class WarehouseRequest extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['destination_name', 'user_name', 'status_text'];

    protected $fillable = [
        'srf_num',
        'destination_id',
        'remarks',
        'user_id',
        'status',
    ];

    /**
     * Boot all of the bootable traits on the model.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = WarehouseRequest::all()->last();
            if(!empty($latest)){
                $model->srf_num = ($latest->srf_num != null) ? $latest->srf_num + 1: 1;
            }else{
                $model->srf_num = 1;
            }

        });
    }

    public function warehouse_request_items()
    {
        return $this->hasMany(WarehouseRequestItem::class, 'warehouse_request_id');
    }

    public function destination()
    {
        return $this->belongsTo(Warehouse::class, 'destination_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDestinationNameAttribute(){
        $destination_name = !empty($this->destination) ? $this->destination->name : '';
        unset($this->destination);
        return $destination_name;
    }

    public function getUserNameAttribute(){
        $user_name = !empty($this->user) ? $this->user->name : '';
        unset($this->user);
        return $user_name;
    }

    public function getStatusTextAttribute()
    {
    /*
        statuses
        0 = 'pending'
        1 = 'approved'
        2 = 'completed'
        3 = 'rejected'
        4 = 'canceled'
        5 = 'deleted'
    */

        if($this->status==0){
            return 'pending';
        }elseif($this->status==1){
            return 'approved';
        }elseif($this->status==2){
            return 'completed';
        }elseif($this->status==3){
            return 'rejected';
        }elseif($this->status==4){
            return 'canceled';
        }elseif($this->status==5){
            return 'deleted';
        }
    }
}
