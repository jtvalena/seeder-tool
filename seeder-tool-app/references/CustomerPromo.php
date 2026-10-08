<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Sales\Promo;
use App\Models\MasterData\Customer;

class CustomerPromo extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['customer_name'];

    protected $fillable = [
        'promo_id',
        'customer_id',
        'discount_rate',
        'cash_price',
        'disabled'
    ];

    public function promo()
    {
        return $this->belongsTo(Promo::class);
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


    /**
     * Scope a query to return customer promos that are not disabled.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeEnabled($query)
    {
        return $query->where('customer_promos.disabled', '=', 0);
    }
}
