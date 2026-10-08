<?php

namespace App\Models;

use App\Models\MasterData\Customer;
use App\Models\User;
use App\Models\Operations\Warehouse;
use Illuminate\Database\Eloquent\Model;
use App\Models\WarehouseReturnItem;
use OwenIt\Auditing\Contracts\Auditable;

class WarehouseReturn extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['customer_name','warehouse','creator'];

    protected $fillable = [
        'series',
        'check_by',
        'validated_by',
        'approve_by',
        'deleted_ay',
        'status',
        'customer_id',
        'note',
        'source',
        'doc_num'
    ];

    protected $attributes = [
        'series',
        'check_by',
        'validated_by',
        'approve_by',
        'deleted_ay',
        'status',
        'customer_id',
        'note',
    ];

    public function warehouse_return_items()
    {
        return $this->hasMany(WarehouseReturnItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function warehouse_name()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function creator_name()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function getCustomerNameAttribute(){
        $customer_name = "";
        if(!empty($this->customer)){
            if($this->customer->type == 'bus'){
                $customer_name = !empty($this->customer->company_name) ? $this->customer->company_name: '';
            }else{
                $customer_name = !empty($this->customer->full_name) ? $this->customer->full_name: '';
            }
        }
        unset($this->customer);
        return $customer_name;
    }

    public function getWarehouseAttribute(){
        $name = !empty($this->warehouse_name) ? $this->warehouse_name->name : '';
        return $name;
    }

    public function getCreatorAttribute(){
        $name = !empty($this->creator_name) ? $this->creator_name->name : '';
        return $name;
    }
}
