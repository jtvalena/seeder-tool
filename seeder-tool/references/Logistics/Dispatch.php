<?php

namespace App\Models\Logistics;

// use App\Events\RealtimeDispatch;
// use App\Events\RealtimeDispatchDashboard;
// use App\Http\Controllers\WarehouseQueueingController;
// use App\Models\CustomerOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Logistics\DispatchItem;
use App\Models\Logistics\DispatchItemWpf;
use App\Models\User;
use App\Models\Vehicle;
use OwenIt\Auditing\Contracts\Auditable;

class Dispatch extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['confirmed_by_name' ,'status_text', 'vehicle_name','dispatch_by_name', 'vehicle_is_tpl', 'canceled_by_name'];

    protected $fillable = [
        'vehicle_id',
        'driver_name',
        'dispatcher_name',
        'helper',
        'helper_2',
        'helper_3',
        'dispatched_by',
        'dispatch_date',
        'confirm_date',
        'confirmed_by',
        'dispatch_by',
        'status',
        'forwarder_name',
        'remarks',
        'vehicle_tpl',
        'truck_type',
        'printed',
        'print_counter',
        'cancel_reason',
        'canceled_by',
    ];

    public function dispatch_items()
    {
        return $this->hasMany(DispatchItem::class);
    }

    public function dispatch_item_pls()
    {
        return $this->hasMany(DispatchItemPl::class);
    }

    public function dispatch_item_wpfs()
    {
        return $this->hasMany(DispatchItemWpf::class);
    }

    public function print_histories()
    {
        return $this->hasMany(DispatchPrintHistory::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function confirmed_by_user()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function dispatch_by_user()
    {
        return $this->belongsTo(User::class,'dispatch_by','id');
    }

    public function canceled_by_user()
    {
        return $this->belongsTo(User::class, 'canceled_by');
    }

    public function getConfirmedByNameAttribute(){
        $name = !empty($this->confirmed_by_user) ? $this->confirmed_by_user->name : '';
        unset($this->confirmed_by_user);
        return $name;
    }

    public function getDispatchByNameAttribute(){
        $name = !empty($this->dispatch_by_user) ? $this->dispatch_by_user->name : '';
        unset($this->dispatch_by_user);
        return $name;
    }

    public function getVehicleNameAttribute(){
        $name = !empty($this->vehicle) ? $this->vehicle->name : '';
        unset($this->vehicle);
        return $name;
    }

    public function getVehicleIsTplAttribute(){
        $tpl = !empty($this->vehicle) ? $this->vehicle->tpl : 0;
        unset($this->vehicle);
        return $tpl;
    }

    public function getCanceledByNameAttribute(){
        $name = !empty($this->canceled_by_user) ? $this->canceled_by_user->name : '';
        unset($this->canceled_by_user);
        return $name;
    }

    public function getStatusTextAttribute()
    {
        if($this->status==0){
            return 'saved';
        }elseif($this->status==1){
            return 'confirmed';
        }elseif($this->status==2){
            return 'dispatched';
        }elseif($this->status==3){
            return 'submitted';
        }elseif($this->status==4){
            return 'canceled';
        }
    }

    public static function boot()
    {
        parent::boot();

        // static::created(function ($model) {
        //     broadcast(new RealtimeDispatch(WarehouseQueueingController::queueDispatch('save',$model->id)));
        // });

        static::updated(function ($model) {
            if($model->wasChanged('status')){
                // broadcast(new RealtimeDispatch(WarehouseQueueingController::queueDispatch('update',$model->id)));
                $method = $model->status == 2 ? 'busy' : 'available';

                if(in_array($model->status,[1,2])){

                    // $customer_order = CustomerOrder::join('sales_quotations','sales_quotations.customer_order_id','customer_orders.id')
                    // ->select('customer_orders.*')
                    // ->join('sales_orders','sales_orders.sq_id','sales_quotations.id')
                    // ->join('packing_lists','packing_lists.so_id','sales_orders.id')
                    // ->join('dispatches','dispatches.id','packing_lists.dispatch_id')
                    // ->where('dispatches.id',$model->id)
                    // ->first();

                    // if($customer_order){
                    //     $customer_order->status = $model->status  == 2 ? 4 : 5;
                    //     $customer_order->save();
                    // }

                }
                // broadcast(new RealtimeDispatchDashboard(WarehouseQueueingController::queueDispatchDashboard($model->vehicle_id,$method)));
            }
        });

        static::deleting(function ($model) {
            // Delete all dispatch_item_wpfs records when dispatch is deleted (if they exist)
            $dispatchItemWpfs = DispatchItemWpf::where('dispatch_id', $model->id);
            if ($dispatchItemWpfs->exists()) {
                $dispatchItemWpfs->delete();
            }
        });

    }
}
