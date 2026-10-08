<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Bundle;
use App\Models\MasterData\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;

class BundleCustomer extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $appends = ['customer_name'];

    protected $fillable = [
        'bundle_id',
        'customer_id',
        'cash_price',
        'disabled',
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
}
