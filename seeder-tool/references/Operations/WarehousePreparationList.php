<?php

namespace App\Models\Operations;

use App\Events\RealtimeWarehouseQueueing;
use App\Events\RealtimeWarehouseQueueingHT;
use App\Http\Controllers\WarehouseQueueingController;
use App\Models\CustomerOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\Sales\SalesOrder;
use App\Models\Operations\Warehouse;;
use App\Models\User;
use App\Models\Operations\CheckerForm;
use App\Models\Operations\WpfGroup;
use App\Models\WarehousePreparationListMovement;
use App\Models\Logistics\DispatchItemWpf;
use App\Models\Sales\PackingList;
use App\Models\Logistics\DispatchItemPl;
use App\Models\Logistics\Dispatch;
use OwenIt\Auditing\Contracts\Auditable;


class WarehousePreparationList extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = [
        'issuer_name',
        'completed_by_name',
        'cancelled_by_name',
        'status_text',
        'customer_name',
        'sales_order_num',
        'sales_quotation_num',
        'warehouse_name',
        'warehouse_code',
        'return_warehouse_name',
        'return_warehouse_code',
        'checker_form_status',
        'sales_order_terms',
        'sales_order_remarks',
        'notes',
        'wpf_group_name',
    ];

    protected $fillable = [
        'user_id',
        'status',
        'completed_by',
        'complete_date',
        'cancelled_by',
        'cancelled_at',
        'cancelled_reason',
        'so_id',
        'warehouse_id',
        'return_warehouse_id',
        'stock_card',
        'printed',
        'print_counter',
        'prepare_date',
        'prepare_comment',
        'prepare_processed_by',
        'prepare_processed_date',
        'picking_correction',
        'correct_picking_reason',
        'for_partial',
        'item_type_id',
        'wpf_group_id',
        'trade',
        'crate_number',
        'service',
        'wpf_num',
        'brand_id',
        'pullout_customer_order',
        'preparer_name',
        'checker_name',
        'num_of_box',
        'remarks',
        'start_date',
        'end_date',
        'durations',
        'dispatch_status',
    ];

    protected $casts = [
        'prepare_date' => 'datetime',
        'complete_date' => 'datetime',
        'cancelled_at' => 'datetime',
        'prepare_processed_date' => 'datetime',
    ];

    public function warehouse_preparation_list_items()
    {
        return $this->hasMany(WarehousePreparationListItem::class, 'wp_list_id');
    }

    // Alias for warehouse_preparation_list_items (for convenience)
    public function wpf_items()
    {
        return $this->hasMany(WarehousePreparationListItem::class, 'wp_list_id');
    }

    public function sales_order()
    {
        // return $this->belongsTo(SalesOrder::class, 'so_id')->withTrashed();
        return $this->belongsTo(SalesOrder::class, 'so_id');
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

    public function cancelled_by_user()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function warehouse()
    {
        // return $this->belongsTo(Warehouse::class, 'warehouse_id')->withTrashed();
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function return_warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'return_warehouse_id');
    }

    public function printHistory()
    {
        return $this->hasMany(WpfPrintHistory::class, 'wpf_id');
    }

    public function checker_form()
    {
        return $this->hasOne(CheckerForm::class, 'wp_list_id');
    }

    public function wpf_group()
    {
        return $this->belongsTo(WpfGroup::class, 'wpf_group_id');
    }

    public function dispatch_item_wpfs()
    {
        return $this->hasMany(\App\Models\Logistics\DispatchItemWpf::class, 'wpf_id');
    }

    public function getSalesOrderNumAttribute(){
        $num = !empty($this->sales_order) ? $this->sales_order->order_num : '';
        return $num;
    }

    public function getSalesQuotationNumAttribute(){
        $num = !empty($this->sales_order->sales_quotation) ? $this->sales_order->sales_quotation->quote_num : '';
        return $num;
    }

    public function getIssuerNameAttribute(){
        $issuer_name = !empty($this->issuer) ? $this->issuer->name : '';
        return $issuer_name;
    }

    public function getCompletedByNameAttribute(){
        $name = !empty($this->completed_by_user) ? $this->completed_by_user->name : '';
        return $name;
    }

    public function getCancelledByNameAttribute(){
        $name = !empty($this->cancelled_by_user) ? $this->cancelled_by_user->name : '';
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
        return $customer_name;
    }

    public function getWarehouseNameAttribute(){
        $warehouse_name = '';
        if(!empty($this->warehouse)){
            $warehouse_name = $this->warehouse->name ?? '';
        }
        return $warehouse_name;
    }

    public function getWarehouseCodeAttribute(){
        $warehouse_code = '';
        if(!empty($this->warehouse)){
            $warehouse_code = $this->warehouse->code ?? '';
        }
        return $warehouse_code;
    }

    public function getReturnWarehouseNameAttribute(){
        if (!empty($this->return_warehouse)) {
            return $this->return_warehouse->name ?? '';
        }
        return $this->warehouse_name;
    }

    public function getReturnWarehouseCodeAttribute(){
        if (!empty($this->return_warehouse)) {
            return $this->return_warehouse->code ?? '';
        }
        return $this->warehouse_code;
    }

    public function getCheckerFormStatusAttribute(){
        if(!empty($this->checker_form)){
            return $this->checker_form->status;
        }else{
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

    public function getNotesAttribute(){
        if(!empty($this->sales_order)){
            return $this->sales_order->notes;
        }else{
            return null;
        }
    }

    public function getWpfGroupNameAttribute(){
        if(!empty($this->wpf_group)){
            return $this->wpf_group->name;
        }else{
            return null;
        }
    }

    public function getStatusTextAttribute()
    {
        /*
            0 = 'preparing'
            1 = 'prepared'
            2 = 'rejected'
            3 = 'canceled'
            4 = 'deleted'
            5 = 'completed'
            6 = 'partially prepared'
            7 = 'waiting for stocks'
            8 = 'submitted'
            9 = 'missed'
            10 = 'priority'
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
        }elseif($this->status==6){
            return 'partially prepared';
        }elseif($this->status==7){
            return 'waiting for stocks';
        }elseif($this->status==8){
            return 'submitted';
        }elseif($this->status==9){
            return 'missed';
        }elseif($this->status==10){
            return 'priority';
        }
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $check_type_id = WarehouseQueueingController::checkTypeIdSo($model->so_id);
            $currentDate = date('Y-m-d');
            $updatedDate = $model->prepare_date->toDateString();

            if($updatedDate == $currentDate){
                if($check_type_id == 1){
                    broadcast(new RealtimeWarehouseQueueing(WarehouseQueueingController::queueWpf('save',$model->id,$check_type_id), Auth::id() ?? $model->user_id ?? 0));
                }
                else if($check_type_id == 2){
                    broadcast(new RealtimeWarehouseQueueingHT(WarehouseQueueingController::queueWpf('save',$model->id,$check_type_id), Auth::id() ?? $model->user_id ?? 0));
                }
            }

            if($model->status == 0){
                // self::customerOrderStatus($model);
            }

            WarehousePreparationListMovement::create([
                'wp_list_id' => $model->id,
                'status' => $model->status,
                'updated_by' => Auth::id() ?? $model->user_id ?? 0,
                'complete_date' => $model->complete_date,
            ]);
        });

        static::updated(function ($model) {
            if($model->wasChanged('prepare_date')){
                $currentDate = date('Y-m-d');
                $updatedDate = $model->prepare_date;

                if($updatedDate == $currentDate){
                    $check_type_id = WarehouseQueueingController::checkTypeIdSo($model->id);

                        if($check_type_id == 1){
                            broadcast(new RealtimeWarehouseQueueing(WarehouseQueueingController::queueWpf('save',$model->id,$check_type_id), Auth::id() ?? $model->user_id ?? 0));
                        }
                        else if($check_type_id == 2){
                            broadcast(new RealtimeWarehouseQueueingHT(WarehouseQueueingController::queueWpf('save',$model->id,$check_type_id), Auth::id() ?? $model->user_id ?? 0));
                        }
                }
            }

            if($model->status == 0){
                // self::customerOrderStatus($model);
            }
            if($model->wasChanged('status')){
                $convert_to_cf = WarehouseQueueingController::queueCf('update',$model->id,'');
                broadcast(new RealtimeWarehouseQueueing($convert_to_cf, Auth::id() ?? $model->user_id ?? 0));
                broadcast(new RealtimeWarehouseQueueingHT($convert_to_cf, Auth::id() ?? $model->user_id ?? 0));

                WarehousePreparationListMovement::create([
                    'wp_list_id' => $model->id,
                    'status' => $model->status,
                    'updated_by' => Auth::id() ?? $model->completed_by ?? $model->user_id ?? 0,
                    'complete_date' => $model->complete_date,
                ]);
            }
        });
    }

    public static function customerOrderStatus($data){

            if($data->status == 0){
                CustomerOrder::leftjoin('sales_quotations','customer_orders.id','sales_quotations.customer_order_id')
                ->leftjoin('sales_orders','sales_orders.sq_id','sales_quotations.id')
                ->leftjoin('warehouse_preparation_lists','warehouse_preparation_lists.so_id','sales_orders.id')
                ->where('warehouse_preparation_lists.id',$data->id)
                ->update(['customer_orders.status'=>3]);
            }
    }

    /**
     * Determine the dispatch status for a WPF based on the seeder logic
     * Status criteria:
     * 1. 'dispatched' - WPF id found in packing_lists, dispatch_id is not null and status != 3
     * 2. 'submitted' - WPF id found in dispatch_item_wpfs with 1 unique dispatch_id only,
     *    and no packing_lists instance found when checking wpf_id and dispatch_id with status != 3
     * 3. 'n/a' - WPF id cannot be found in dispatch_item_wpfs OR
     *    traced through PL via CF but cannot be found in dispatch_item_pls
     *
     * @param int|null $wpfId If null, uses $this->id
     * @return string 'n/a', 'submitted', or 'dispatched'
     */
    public function determineDispatchStatus($wpfId = null)
    {
        $wpfId = $wpfId ?? $this->id;
        
        if (!$wpfId) {
            return 'n/a';
        }

        // Reuse the already-loaded instance when the ID matches to avoid a redundant DB round-trip
        $wpf = ($wpfId == $this->id) ? $this : self::find($wpfId);
        if (!$wpf || $wpf->service != 0) {
            return 'n/a';
        }

        // Load dispatch_item_wpfs for this WPF
        $dispatchItemWpfs = DispatchItemWpf::where('wpf_id', $wpfId)->get();

        // Load packing_lists for this WPF
        $packingLists = PackingList::where('wpf_id', $wpfId)
            ->whereNull('deleted_at')
            ->get();

        // Priority 1: Check criterion 4 - dispatched
        // WPF id found in packing_lists, dispatch_id is not null and status != 3
        $dispatchedPL = $packingLists->first(function ($pl) {
            return !is_null($pl->dispatch_id) && $pl->status != 3;
        });

        if ($dispatchedPL) {
            return 'dispatched';
        }

        // Priority 2: Check if WPF is in dispatch_item_wpfs
        if ($dispatchItemWpfs->count() > 0) {
            // Get unique dispatch IDs
            $uniqueDispatchIds = $dispatchItemWpfs->pluck('dispatch_id')->unique()->filter()->values();
            $uniqueDispatchCount = $uniqueDispatchIds->count();

            // Check criterion 2 - submitted
            // Has 1 unique dispatch_id only
            // No packing_lists instance found when checking wpf_id and dispatch_id with status != 3
            if ($uniqueDispatchCount === 1) {
                $dispatchId = $uniqueDispatchIds->first();

                $packingListWithDispatch = $packingLists->first(function ($pl) use ($dispatchId) {
                    return $pl->dispatch_id == $dispatchId && $pl->status != 3;
                });

                if (!$packingListWithDispatch) {
                    return 'submitted';
                }
            }
        }

        // Priority 3: Check through PL via CF if not found in dispatch_item_wpfs
        if ($dispatchItemWpfs->count() === 0) {
            $checkerForms = CheckerForm::where('wp_list_id', $wpfId)
                ->whereNull('deleted_at')
                ->get();

            if ($checkerForms->count() > 0) {
                $checkerForm = $checkerForms->first();

                // Check if PL exists in dispatch_item_pls
                $dispatchItemPls = DispatchItemPl::where('checker_form_id', $checkerForm->id)->get();

                if ($dispatchItemPls->count() > 0) {
                    $dispatchItemPl = $dispatchItemPls->first();

                    // Check dispatch status
                    $dispatch = Dispatch::where('id', $dispatchItemPl->dispatch_id)
                        ->whereNull('deleted_at')
                        ->first();

                    if ($dispatch) {
                        // If dispatch exists and status is 2 (dispatched), return 'dispatched'
                        if ($dispatch->status == 2) {
                            return 'dispatched';
                        }

                        // Otherwise, check if there's a PL with dispatch_id
                        $plWithDispatch = $packingLists->first(function ($pl) {
                            return !is_null($pl->dispatch_id) && $pl->status != 3;
                        });

                        if ($plWithDispatch) {
                            return 'dispatched';
                        }
                    }
                }
            }
        }

        // Default: Criterion 1 - n/a
        return 'n/a';
    }

    /**
     * Update dispatch_status for this WPF using the determineDispatchStatus logic
     * Only updates if service = 0
     *
     * @return bool True if updated, false otherwise
     */
    public function updateDispatchStatus()
    {
        if ($this->service != 0) {
            return false;
        }

        $newStatus = $this->determineDispatchStatus();
        
        if ($this->dispatch_status != $newStatus) {
            $this->dispatch_status = $newStatus;
            return $this->save();
        }

        return false;
    }
}
