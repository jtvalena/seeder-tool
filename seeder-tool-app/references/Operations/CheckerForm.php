<?php

namespace App\Models\Operations;

use App\Events\RealtimeWarehouseQueueing;
use App\Events\RealtimeWarehouseQueueingHT;
use App\Http\Controllers\WarehouseQueueingController;
use App\Models\CheckerFormMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\Sales\SalesOrder;
use App\Models\Operations\Warehouse;
use App\Models\Operations\WarehousePreparationList;
use App\Models\Operations\CheckerFormItem;
use App\Models\Sales\PackingList;
use App\Models\User;
use OwenIt\Auditing\Contracts\Auditable;

class CheckerForm extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = [
        'issuer_name',
        'completed_by_name',
        'status_text',
        'customer_name',
        'sales_order_num',
        'warehouse_name',
        'packing_list_status',
        'sales_order_terms',
        'sales_order_remarks',
        'notes',
        'sales_quotation_num',
     ];

    protected $fillable = [
        'wp_list_id',
        'user_id',
        'status',
        'remarks',
        'checker_name',
        'completed_by',
        'complete_date',
        'so_id',
        'warehouse_id',
        'stock_card',
        'printed',
        'num_of_box',
        'item_type_id',
        'trade',
        'reprint',
        'reprint_date',
        'reprint_reason',
        'service',
        'converted',
        'cf_num',
        'brand_id',
        'pullout_customer_order',
    ];

    public function sales_order()
    {
        // return $this->belongsTo(SalesOrder::class, 'so_id')->withTrashed();
        return $this->belongsTo(SalesOrder::class, 'so_id');
    }

    public function wp_list()
    {
        // return $this->belongsTo(WarehousePreparationList::class, 'wp_list_id')->withTrashed();
        return $this->belongsTo(WarehousePreparationList::class, 'wp_list_id');
    }

    public function checker_form_items()
    {
        return $this->hasMany(CheckerFormItem::class, 'checker_form_id');
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

    public function packing_list()
    {
        // return $this->hasOne(PackingList::class, 'checker_form_id')->withTrashed();
        return $this->hasOne(PackingList::class, 'checker_form_id')->orderBy('packing_lists.id', 'desc');
    }

    public function getSalesOrderNumAttribute(){
        $num = !empty($this->sales_order) ? $this->sales_order->order_num : '';
        unset($this->sales_order);
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
        unset($this->sales_order->customer);
        return $customer_name;
    }

    public function getWarehouseNameAttribute()
    {
        $warehouse_name = '';
        if (!empty($this->warehouse)) {
            $warehouse_name = $this->warehouse->name ?? '';
        }
        unset($this->warehouse);
        return $warehouse_name;
    }

    public function getPackingListStatusAttribute()
    {
        if (!empty($this->packing_list)) {
            return $this->packing_list->status;
        } else {
            return -1;
        }
    }

    public function getSalesOrderTermsAttribute(){
        if(!empty($this->sales_order)){
            return $this->sales_order->terms;
        }else{
            return null;
        }
    }

    public function getSalesOrderRemarksAttribute(){
        if(!empty($this->sales_order)){
            return $this->sales_order->remarks;
        }else{
            return null;
        }
    }

    public function getNotesAttribute()
    {
        return $this->sales_order->notes;
    }

    public function getSalesQuotationNumAttribute(){
        $num = !empty($this->sales_order->sales_quotation) ? $this->sales_order->sales_quotation->quote_num : '';
        return $num;
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

        if ($this->status == 0) {
            return 'checking';
        } elseif ($this->status == 1) {
            return 'checked';
        } elseif ($this->status == 2) {
            return 'rejected';
        } elseif ($this->status == 3) {
            return 'canceled';
        } elseif ($this->status == 4) {
            return 'deleted';
        } elseif ($this->status == 5) {
            return 'completed';
        } elseif ($this->status == 6)  {
            return 'prepared';
        }
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            CheckerFormMovement::create([
                'checker_form_id' => $model->id,
                'status' => $model->status,
                'updated_by' => Auth::id() ?? $model->user_id,
                'complete_date' => $model->complete_date,
            ]);
        });

        static::updated(function ($model) {

            if($model->wasChanged('status')){
                // $check_type_id = WarehouseQueueingController::checkTypeId($model->id);
                $convert_to_pl = WarehouseQueueingController::queueCf('update',$model->id,'');
                broadcast(new RealtimeWarehouseQueueing($convert_to_pl, Auth::id() ?? $model->user_id ?? 0));
                broadcast(new RealtimeWarehouseQueueingHT($convert_to_pl, Auth::id() ?? $model->user_id ?? 0));

                CheckerFormMovement::create([
                    'checker_form_id' => $model->id,
                    'status' => $model->status,
                    'updated_by' => Auth::id() ?? $model->user_id,
                    'complete_date' => $model->complete_date,
                ]);
            }
        });
    }

}
