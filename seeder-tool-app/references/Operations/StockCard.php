<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sales\SalesOrder;
use App\Models\Operations\StockCardItem;
use App\Models\MasterData\Customer;
use App\Models\Sales\PackingList;
use App\Models\Operations\Warehouse;
use App\Models\User;
use OwenIt\Auditing\Contracts\Auditable;

class StockCard extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['issuer_name', 'completed_by_name', 'status_text', 'customer_name', 'sales_order_num', 'packing_list_num', 'warehouse_name', 'checker_form_status', 'shipping_address_text'];

    protected $fillable = [
        'user_id',
        'status',
        'completed_by',
        'complete_date',
        'so_id',
        'warehouse_id',
        'packing_list_id',
        'stock_card_date',
        'printed',
    ];

    public function sales_order()
    {
        // return $this->belongsTo(SalesOrder::class, 'so_id')->withTrashed();
        return $this->belongsTo(SalesOrder::class, 'so_id');
    }

    public function packing_lists()
    {
        // return $this->belongsTo(PackingList::class, 'packing_list_id')->withTrashed();
        return $this->belongsTo(PackingList::class);
    }

    public function stock_card_items()
    {
        return $this->hasMany(StockCardItem::class, 'stock_card_id');
    }

    public function issuer()
    {
        // return $this->belongsTo(User::class, 'user_id')->withTrashed();
        return $this->belongsTo(User::class, 'user_id');
    }

    public function completed_by_user()
    {
        // return $this->belongsTo(User::class, 'completed_by')->withTrashed();
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function warehouse()
    {
        // return $this->belongsTo(Warehouse::class, 'warehouse_id')->withTrashed();
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function customer()
    {
        // return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = StockCard::select('stock_card_num')->whereRaw('length(stock_card_num)=6')->max('stock_card_num');
            if (!empty($latest)) {
                $next_stock_card_num = ($latest != null) ? intval($latest) + 1 : 1;
                $model->stock_card_num = str_pad((string)$next_stock_card_num, 6, '0', STR_PAD_LEFT);
            } else {
                $model->stock_card_num = str_pad('1', 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getSalesOrderNumAttribute(){
        $num = !empty($this->sales_order) ? $this->sales_order->order_num : '';
        unset($this->sales_order);
        return $num;
    }

    public function getPackingListNumAttribute()
    {
        $num = !empty($this->packing_list) ? $this->packing_list->packing_list_num : '';
        unset($this->packing_list);
        return $num;
    }

    public function getIssuerNameAttribute(){
        $issuer_name = !empty($this->issuer) ? $this->issuer->name : '';
        unset($this->issuer);
        return $issuer_name;
    }

    public function getCompletedByNameAttribute(){
        $name = !empty($this->completed_by_user) ? $this->completed_by_user->name : '';
        unset($this->issuer);
        return $name;
    }

    public function getCustomerNameAttribute(){
        $customer_name = "";
        if(!empty($this->sales_order->customer)){
            if($this->sales_order->customer->type == 'bus'){
                $customer_name = !empty($this->sales_order->customer->company_name) ? $this->sales_order->customer->company_name: '';
            }else{
                $customer_name = !empty($this->sales_order->customer->full_name) ? $this->sales_order->customer->full_name: '';
            }
        }
        //unset($this->sales_order->customer);
        return $customer_name;
    }

    public function getWarehouseNameAttribute(){
        $warehouse_name = '';
        if(!empty($this->warehouse)){
            $warehouse_name = $this->warehouse->name ?? '';
        }
        unset($this->warehouse);
        return $warehouse_name;
    }

    public function getCheckerFormStatusAttribute(){
        if(!empty($this->checker_form)){
            return $this->checker_form->status;
        }else{
            return -1;
        }
    }

    public function getShippingAddressTextAttribute()
    {
        if (!empty($this->packing_list)) {
            return $this->packing_list->shipping_address_text;
        }
    }

    public function getStatusTextAttribute()
    {
        /*
            0 = 'pending'
            1 = 'confirmed'
            2 = 'rejected'
            3 = 'canceled'
            4 = 'deleted'
            5 = 'completed'
        */

        if($this->status==0){
            return 'preparing';
        }elseif($this->status==1){
            return 'prepared';
        }elseif($this->status==2){
            return 'rejected';
        }elseif($this->status==3){
            return 'canceled';
        }elseif($this->status==4){
            return 'deleted';
        }elseif($this->status==5){
            return 'completed';
        }
    }

}
