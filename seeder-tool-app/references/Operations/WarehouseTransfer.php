<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Operations\Warehouse;
use App\Models\User;

class WarehouseTransfer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['source_name', 'destination_name', 'user_name', 'status_text'];

    protected $fillable = [
        'warehouse_request_id',
        'srf_num',
        'source_id',
        'destination_id',
        'remarks',
        'user_id',
        'status',
    ];

    /*
        statuses
        0 = 'pending'
        1 = 'approved'
        2 = 'completed'
        3 = 'rejected'
    */

    public function warehouse_transfer_items()
    {
        return $this->hasMany(WarehouseTransferItem::class, 'warehouse_transfer_id');
    }

    public function warehouse_request()
    {
        return $this->belongsTo(WarehouseRequest::class);
    }

    public function source()
    {
        return $this->belongsTo(Warehouse::class, 'source_id');
    }

    public function destination()
    {
        return $this->belongsTo(Warehouse::class, 'destination_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSourceNameAttribute(){
        $source_name = !empty($this->source) ? $this->source->name : '';
        unset($this->source);
        return $source_name;
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
