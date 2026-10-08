<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\DispatchItem;
use App\Models\Vehicle;
use OwenIt\Auditing\Contracts\Auditable;

class Dispatch extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['confirmed_by_name' ,'status_text', 'vehicle_name'];

    protected $fillable = [
        'vehicle_id',
        'driver_name',
        'dispatcher_name',
        'helper',
        'helper_2',
        'helper_3',
        'dispatch_date',
        'confirm_date',
        'confirmed_by',
        'status',
    ];

    public function dispatch_items()
    {
        return $this->hasMany(DispatchItem::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function confirmed_by_user()
    {
        return $this->belongsTo(Vehicle::class, 'confirmed_by');
    }

    public function getConfirmedByNameAttribute(){
        $name = !empty($this->confirmed_by_user) ? $this->confirmed_by_user->name : '';
        unset($this->confirmed_by_user);
        return $name;
    }

    public function getVehicleNameAttribute(){
        $name = !empty($this->vehicle) ? $this->vehicle->name : '';
        unset($this->vehicle);
        return $name;
    }

    public function getStatusTextAttribute()
    {
        if($this->status==0){
            return 'saved';
        }elseif($this->status==1){
            return 'confirmed';
        }
    }
}
