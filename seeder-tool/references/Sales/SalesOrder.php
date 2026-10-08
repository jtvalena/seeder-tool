<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MasterData\Customer;
use App\Models\Sales\Promo;
use App\Models\MasterData\Salesman;
use App\Models\Sales\SalesOrderItem;
use App\Models\Sales\SalesQuotation;
use App\Models\Operations\WarehousePreparationList;
use App\Models\Operations\CheckerForm;
use App\Models\Sales\PackingList;
use App\Models\SalesOrderMovement;
use App\Models\User;
use App\Traits\HasAttributeChangesToBroadcast;
use App\Traits\MorphMap;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class SalesOrder extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use MorphMap, HasAttributeChangesToBroadcast;

    protected $appends = ['customer_name', 'salesman_name', 'issuer_name', 'status_text', 'override_by_name' , 'approved_by_name', 'acc_approved_by_name',
            'approve_exec_enabled', 'promo_name', 'fully_served', 'delivery_method_text', 'movement_status_text','store_customer', 'quote_num', 'canceled_by_name',
            // 'notes',
            'delivery_processed_by_name'
    ];

    protected $fillable = [
        'order_num',
        'customer_id',
        'salesman_id',
        'user_id',
        'remarks',
        'total_amount',
        'discounts',
        'terms',
        'convert_date',                 //date when this is converted from SQ to SO
        'status',
        'shipping_address_text',
        'shipping_address_id',
        'override_by',
        'discount_amount',
        'gross_amount',
        'submitted_by',
        'converted_by',
        'approved_by',                  //approved by manager
        'acc_approved_by',              //approved by accounting
        'completed_by',
        'deleted_by',
        'submit_date',
        'approve_date',
        'acc_approved_date',
        'complete_date',
        'sq_id',
        'exec_approved_date',
        'exec_approver_id',
        'promo_id',
        'special',
        'customer_po_num',
        'stock_card',
        'delivery_method',
        'canceled_by',
        'cancel_date',
        'cancel_remarks',
        'service',
        're_so',
        'new_so_ids',
        're_so_at',
        'delivery_date',
        'delivery_comment',
        'delivery_processed_by',
        'delivery_processed_date',
        'approve_pl',
        'rush',
        'cancel_amount',
        'item_type_id',
        'trade',
        'brand_id',
        'pullout_customer_order',
    ];

    public function sales_order_items()
    {
        return $this->hasMany(SalesOrderItem::class, 'sales_order_id');
    }

    public function customer()
    {
        // return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function promo()
    {
        // return $this->belongsTo(Promo::class, 'promo_id')->withTrashed();
        return $this->belongsTo(Promo::class, 'promo_id');
    }

    public function issuer()
    {
        // return $this->belongsTo(User::class, 'user_id')->withTrashed();
        return $this->belongsTo(User::class, 'user_id');
    }

    public function override_by_user()
    {
        // return $this->belongsTo(User::class, 'override_by')->withTrashed();
        return $this->belongsTo(User::class, 'override_by');
    }

    public function approved_by_user()
    {
        // return $this->belongsTo(User::class, 'approved_by')->withTrashed();
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function acc_approved_by_user()
    {
        // return $this->belongsTo(User::class, 'acc_approved_by')->withTrashed();
        return $this->belongsTo(User::class, 'acc_approved_by');
    }

    public function canceled_by_user()
    {
        // return $this->belongsTo(User::class, 'canceled_by')->withTrashed();
        return $this->belongsTo(User::class, 'canceled_by');
    }

    public function delivery_processed_by_user()
    {
        // return $this->belongsTo(User::class, 'delivery_processed_by')->withTrashed();
        return $this->belongsTo(User::class, 'delivery_processed_by');
    }

    public function salesman()
    {
        // return $this->belongsTo(Salesman::class)->withTrashed();
        return $this->belongsTo(Salesman::class);
    }

    public function sales_quotation()
    {
        // return $this->belongsTo(SalesQuotation::class, 'sq_id')->withTrashed();
        return $this->belongsTo(SalesQuotation::class, 'sq_id')->with(['sales_quotation_items']);
    }

    public function warehouse_preparation_lists()
    {
        return $this->hasMany(WarehousePreparationList::class, 'so_id');
    }

    public function checker_forms()
    {
        return $this->hasMany(CheckerForm::class, 'so_id');
    }

    public function packing_lists()
    {
        return $this->hasMany(PackingList::class, 'so_id');
    }

    public function packing_lists_confirmed()
    {
        return $this->hasMany(PackingList::class, 'so_id')->where('status', 1);
    }

    /**
     * Boot all of the bootable traits on the model.
     */
    public static function boot()
    {
        parent::boot();
            static::created(function ($model) {
                SalesOrderMovement::create([
                    'sales_order_id' => $model->id,
                    'status' => $model->status,
                    'updated_by' => $model->user_id,
                ]);
            });
            static::updated(function ($model) {
                if($model->wasChanged('status')){
                    SalesOrderMovement::create([
                        'sales_order_id' => $model->id,
                        'status' => $model->status,
                        'updated_by' => !empty(\Auth::id()) ? \Auth::id() : 0,
                    ]);
                }
            });
    //     static::creating(function ($model) {
    //         $latest = SalesOrder::select('order_num')->whereRaw('length(order_num)=6')->max('order_num');
    //         if(!empty($latest)){
    //             $next_order_num = ($latest != null) ? intval($latest) + 1: 1;
    //             $model->order_num = str_pad((string)$next_order_num, 6, '0', STR_PAD_LEFT);
    //         }else{
    //             $model->order_num = str_pad('1', 6, '0', STR_PAD_LEFT);
    //         }
    //     });
    }

    public function getCustomerNameAttribute(){
        if (array_key_exists('customer_name', $this->attributes)) {
            return $this->attributes['customer_name'];
        }
        if (empty($this->customer)) return '';
        return $this->customer->type === 'bus'
            ? ($this->customer->company_name ?? '')
            : ($this->customer->full_name ?? '');
    }

    public function getStoreCustomerAttribute(){
        return !empty($this->customer) && !empty($this->customer->store) ? 1 : 0;
    }

    public function getSalesmanNameAttribute(){
        // Short-circuit when pre-loaded via JOIN in listing queries.
        if (array_key_exists('salesman_name', $this->attributes)) {
            return $this->attributes['salesman_name'];
        }
        return $this->salesman->name ?? '';
    }

    public function getIssuerNameAttribute(){
        // Short-circuit when pre-loaded via JOIN in listing queries.
        if (array_key_exists('issuer_name', $this->attributes)) {
            return $this->attributes['issuer_name'];
        }
        return $this->issuer->name ?? '';
    }

    public function getOverrideByNameAttribute(){
        return $this->override_by_user->name ?? '';
    }

    public function getApprovedByNameAttribute(){
        return $this->approved_by_user->name ?? '';
    }

    public function getAccApprovedByNameAttribute(){
        return $this->acc_approved_by_user->name ?? '';
    }

    public function getPromoNameAttribute(){
        return $this->promo->name ?? '';
    }

    public function getApproveExecEnabledAttribute(){
        return $this->customer->approved_by_exec ?? '';
    }

    public function getFullyServedAttribute()
    {
        // Use withSum() values when loaded by the listing query (no extra queries).
        if (array_key_exists('sales_order_items_sum_quantity', $this->attributes)) {
            $qty    = (int) ($this->attributes['sales_order_items_sum_quantity'] ?? 0);
            $served = (int) ($this->attributes['sales_order_items_sum_quantity_served'] ?? 0);
            return $qty === $served;
        }

        // Fallback for single-record show(): one query instead of two.
        $totals = $this->sales_order_items()
            ->selectRaw('SUM(quantity) as total_qty, SUM(quantity_served) as total_served')
            ->first();

        return (int) ($totals->total_qty ?? 0) === (int) ($totals->total_served ?? 0);
    }

    public function getQuoteNumAttribute()
    {
        // Use the raw attribute when pre-loaded via JOIN in listing queries.
        if (array_key_exists('quote_num', $this->attributes)) {
            return $this->attributes['quote_num'];
        }
        return $this->sales_quotation->quote_num ?? '';
    }

    public function getCanceledByNameAttribute()
    {
        return $this->canceled_by_user->name ?? '';
    }

    public function getDeliveryProcessedByNameAttribute()
    {
        return $this->delivery_processed_by_user->name ?? '';
    }

    // public function getNotesAttribute()
    // {
    //     return $this->sales_quotation->notes;
    // }

    public function getStatusTextAttribute()
    {
        if($this->status==0){
            return 'saved';
        }elseif($this->status==1){
            return 'for sales';
        }elseif($this->status==2){
            return 'for accounting';
        }elseif($this->status==3){
            return 'approved';
        }elseif($this->status==4){
            return 'completed';
        }elseif($this->status==5){
            return 'rejected';
        }elseif($this->status==6){
            return 'canceled';
        }elseif($this->status==7){
            return 'deleted';
        }elseif($this->status==8){
            return 'converted';
        }elseif($this->status==9){
            return 'exec approved';
        }elseif($this->status==10){
            return 'partial';
        }elseif($this->status==11){
            return 'for exec approval';
        }elseif($this->status==12){
            return 'stockcard';
        }elseif ($this->status == 13){
            return 'for cleanup';
        }elseif ($this->status == 14){
            return 'for credit limit';
        }elseif ($this->status == 15){
            return 'for credit terms';
        }elseif ($this->status == 16){
            return 'for scheduling';
        }elseif ($this->status == 17) {
            return 'waiting for stocks';
        }elseif ($this->status == 18) {
            return 'preparing';
        }elseif ($this->status == 19) {
            return 'prepared';
        }elseif ($this->status == 20) {
            return 'checking';
        }elseif ($this->status == 21) {
            return 'checked';
        }elseif ($this->status == 22) {
            return 'invoiced';
        }
    }

    public function getDeliveryMethodTextAttribute()
    {
        /*
            0 = 'none'
            1 = 'pick-up'
            2 = 'delivery'
        */

        if($this->delivery_mthod==0){
            return 'none';
        }elseif($this->delivery_mthod==1){
            return 'pick-up';
        }elseif($this->delivery_mthod==2){
            return 'delivery';
        }
    }

    public function getMovementStatusTextAttribute()
    {
        // $has_wpl = $this->withCount('warehouse_preparation_lists')->value('warehouse_preparation_lists_count') > 0;
        // $has_cf = $this->withCount('checker_forms')->value('checker_forms_count') > 0;
        // $has_pl = $this->withCount('packing_lists')->value('packing_lists_count') > 0;
        // $has_pl_confirmed = $this->withCount('packing_lists_confirmed')->value('packing_lists_confirmed_count') > 0;

        // if ($has_cf && $has_pl) {
        //     return 'in-transit';
        // } elseif ($has_wpl && !$has_cf && !$has_pl) {
        //     return 'schedule';
        // } elseif ($has_pl_confirmed) {
        //     return 'delivered';
        // } else {
        //     return 'pending';
        // }
        return '';
    }

}
