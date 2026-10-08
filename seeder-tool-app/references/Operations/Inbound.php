<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Operations\InboundItem;
use App\Models\Operations\Warehouse;
use App\Models\User;
use App\Traits\MorphMap;
use Illuminate\Support\Facades\DB;

class Inbound extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use MorphMap;

    protected $appends = ['morphMapKey', 'warehouse_name', 'status_text', 'user_name', 'received_by_name','po_number', 'created_by_name'];

    protected $fillable = [
        'inbound_num',
        'confirm_date',
        'remarks',
        'warehouse_id',
        'user_id',
        'status',
        'received_by',
        'received_date',
        'loading_list_id',
        'broker',
        'po_id',
        'created_by',
    ];

    public function inbound_items()
    {
        return $this->hasMany(InboundItem::class, 'inbound_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function getWarehouseNameAttribute(){
        $warehouse_name = !empty($this->warehouse) ? $this->warehouse->name : '';
        unset($this->warehouse);
        return $warehouse_name;
    }

    public function getUserNameAttribute(){
        $user_name = !empty($this->user) ? $this->user->name : '';
        unset($this->user);
        return $user_name;
    }

    public function getReceivedByNameAttribute(){
        $received_by_name = !empty($this->receiver) ? $this->receiver->name : '';
        unset($this->receiver);
        return $received_by_name;
    }

    public function getPoNumberAttribute(){
        $po_number = DB::table('procurement_purchase_orders')->where('id',$this->po_id)->value('po_number');
        return !!$po_number ? $po_number : 'No PO Attached';
    }

    public function getCreatedByNameAttribute()
    {
        $user_name = !empty($this->creator) ? $this->creator->name : '';
        unset($this->creator);
        return $user_name;
    }

    /**
     * Boot all of the bootable traits on the model.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = Inbound::all()->last();
            if(!empty($latest)){
                $model->inbound_num = ($latest->inbound_num != null) ? $latest->inbound_num + 1: 1;
            }else{
                $model->inbound_num = 1;
            }

        });
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
