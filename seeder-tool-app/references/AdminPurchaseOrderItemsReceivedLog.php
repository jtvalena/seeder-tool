<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminPurchaseOrderItemsReceivedLog extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $table = 'admin_purchase_order_items_received_logs';
    protected $fillable = [
        'admin_purchase_order_id',
        'admin_purchase_order_item_id',
        'receive_qty',
        'received_by',
        'receive_paid_amount',
        'admin_item_id'
    ];

    public static function boot()
    {
        parent::boot();
        static::created((function ($model) {
            $consumable_categories = AdminCategories::where('active', 1)->where('consumable', 1)->pluck('id')->toArray();
            $admin_purchase_orders = AdminPurchaseOrder::find($model->admin_purchase_order_id);
            $skipAssetCreation = $admin_purchase_orders && (int) $admin_purchase_orders->exchange_deal === 1;
            $item = AdminItems::find($model->admin_item_id);
            $latest_asset_tag = AdminAssetsList::
            join('admin_items','admin_assets_lists.item_id','admin_items.id')
            ->select('admin_assets_lists.*')
            ->where('admin_items.admin_classification_id',$item->admin_classification_id)
            ->where('admin_assets_lists.store_branch_id', $admin_purchase_orders->store_branch_id)
            ->orderBy('admin_assets_lists.asset_code','desc')
            ->first();
            $asset_num = 0;

            for ($i = 1; $i <=  $model->receive_qty; $i++) {
                if (!!$latest_asset_tag) {
                    if (preg_match('/^(?:[A-Z]{3}-)?[A-Z]+-[A-Z]+-\d{4}-\d{4,5}$/', $latest_asset_tag->asset_code)) {
                        $parts = explode('-', $latest_asset_tag->asset_code);
                        if (count($parts) == 4) {
                            list($store_code, $classification_code, $year, $serial_number) = $parts;
                            $integer = ltrim($serial_number, '0');
                            if ($integer > 0 && $year == Carbon::now()->year) {
                                $asset_num = $integer + $i;
                            } else {
                                $asset_num = $i;
                            }
                        } else {
                            $asset_num = $i;
                        }
                    } else {
                        $asset_num = $i;
                    }
                } else {
                    $asset_num = $i;
                }
                if(in_array($item->item_category, [2,3]) && in_array($item->admin_category_id, $consumable_categories) && !AdminWarehouseItemStock::where('admin_warehouse_id',request()->get('admin_warehouse_id'))->where('admin_item_id',$item->id)->value('id')){
                    $admin_warehouse_item_stocks = new AdminWarehouseItemStock();
                    $admin_warehouse_item_stocks->admin_warehouse_id = request()->get('admin_warehouse_id');
                    // $admin_warehouse_item_stocks->admin_po_id = $admin_purchase_order->id;
                    // $admin_warehouse_item_stocks->admin_po_item_id = $requested_item['id'];
                    $admin_warehouse_item_stocks->quantity = 0;
                    $admin_warehouse_item_stocks->unaccounted = 0;
                    $admin_warehouse_item_stocks->check_in = 0;
                    $admin_warehouse_item_stocks->admin_item_id = $item->id;
                    $admin_warehouse_item_stocks->save();
                }
                if(!$skipAssetCreation && in_array($item->item_category, [1,3]) && !in_array($item->admin_category_id, $consumable_categories)){
                    $get_basket_item_id = AdminPurchaseOrderItem::where('id',$model->admin_purchase_order_item_id)->first();
                    $code_of_store = StoreBranch::where('id', $admin_purchase_orders->store_branch_id)->value('code');
                    $assets = AdminAssetsList::create([
                        // 'asset_num' => $asset_num,
                        // 'asset_code' => $code_of_store. '-'. AdminClassification::where('id',$item->admin_classification_id)->value('code'). "-". Carbon::now()->year. "-". str_pad($asset_num, 4, '0', STR_PAD_LEFT),
                        'asset_code' => 'xxxx-xx-0000-0000',
                        'name' => $item->item_name,
                        'supplier_id' => $admin_purchase_orders->vendor_id,
                        'item_id' => $item->id,
                        'admin_purchase_order_item_id' => $model->admin_purchase_order_item_id,
                        'status' => 20,
                        'active' => 1,
                        'accounted' => 0,
                        'printed' => 0,
                        // 'serial' => $admin_purchase_orders->po_num. " | ". $item->item_name,
                        // 'classification_id' => $item->category_id,
                        // 'sub_classification_id' => $item->sub_category_id,
                        'warranty' => 7,
                        'description' => null,
                        'company_id' => $admin_purchase_orders->company_id,
                        'store_branch_id' => $admin_purchase_orders->store_branch_id,
                        'purchase_price' => $get_basket_item_id->dr_price,
                        'warehouse_id' => request()->get('admin_warehouse_id'),
                        'basket_item_id'=>$get_basket_item_id->basket_item_id,
                        'admin_warehouse_item_stocks_id' => AdminWarehouseItemStock::where('admin_warehouse_id',request()->get('admin_warehouse_id'))->where('admin_item_id',$item->id)->value('id'),
                    ]);
                    if($assets){
                        $have_components = AdminItemPackagesComponents::
                        leftjoin('admin_items','admin_item_packages_components.package_admin_item_id','admin_items.id')
                        ->select(
                            'admin_item_packages_components.*',
                            'admin_items.admin_classification_id'
                        )
                        ->where('admin_item_packages_components.package_admin_item_id', '>', 0)
                        ->where('admin_item_packages_components.admin_item_id',$assets->item_id)->get();
                    
                        if($have_components->count() > 0){
                            foreach($have_components as $components){
                                $latest_asset_tag_children = AdminAssetsList::
                                join('admin_items','admin_assets_lists.item_id','admin_items.id')
                                ->select('admin_assets_lists.*')
                                ->where('admin_items.admin_classification_id',$components->admin_classification_id)
                                ->orderBy('admin_assets_lists.id','desc')
                                ->limit(1)
                                ->first();
                                $asset_num_children = 0;
                                if (!!$latest_asset_tag_children) {
                                    if (preg_match('/^(?:[A-Z]{3}-)?[A-Z]+-[A-Z]+-\d{4}-\d{4,5}$/', $latest_asset_tag_children->asset_code)) {
                                        $parts = explode('-', $latest_asset_tag_children->asset_code);
                                        if (count($parts) === 4) {
                                            list($store_code, $classification_code, $year, $serial_number) = $parts;
                                            $integer = ltrim($serial_number, '0');
                                            if ($integer > 0 && $year == Carbon::now()->year) {
                                                $asset_num_children = $integer + $i;
                                            } else {
                                                $asset_num_children = $i;
                                            }
                                        } else {
                                            $asset_num_children = $i;
                                        }
                                    } else {
                                        $asset_num_children = $i;
                                    }
                                } else {
                                    $asset_num_children = $i;
                                }
                                $adminItems = AdminItems::where('id',$components->package_admin_item_id)->first();
                                $code_of_store_children = StoreBranch::where('id', $admin_purchase_orders->store_branch_id)->value('code');
                                AdminAssetsList::create([
                                    // 'asset_num' => $asset_num,
                                    // 'asset_code' => $code_of_store_children . '-' . AdminClassification::where('id',$components->admin_classification_id)->value('code'). "-". Carbon::now()->year. "-". str_pad($asset_num_children, 4, '0', STR_PAD_LEFT),
                                    'asset_code' => 'xxxx-xx-0000-0000',
                                    'name' => $adminItems->item_name,
                                    'supplier_id' => $assets->supplier_id,
                                    'item_id' => $adminItems->id,
                                    'admin_purchase_order_item_id' => $assets->admin_purchase_order_item_id,
                                    'status' => 20,
                                    'active' => 1,
                                    'printed' => 0,
                                    // 'serial' => $admin_purchase_orders->po_num. " | ". $item->item_name,
                                    // 'classification_id' => $item->category_id,
                                    // 'sub_classification_id' => $item->sub_category_id,
                                    'warranty' => 7,
                                    'description' => null,
                                    'company_id' => $assets->company_id,
                                    'warehouse_id' => $assets->warehouse_id,
                                    'basket_item_id'=>$assets->basket_item_id,
                                    'admin_warehouse_item_stocks_id' => AdminWarehouseItemStock::where('admin_warehouse_id',$assets->warehouse_id)->where('admin_item_id',$adminItems->id)->value('id'),
                                    'parent_asset_id'=>$assets->id,
                                    'accounted'=> 0,
                                ]);
                            }
                        }
                    }
                }
            }
            // Get the accountable employee and quantity
            $po_item = AdminPurchaseOrderItem::find($model->admin_purchase_order_item_id);
            if(!empty($po_item) && !$skipAssetCreation){
                $basket_item = AdminFasBasketItems::find($po_item->basket_item_id);
                if(!empty($basket_item)){
                    $requisiton_item_id = $basket_item->admin_fas_requisition_item_id;
                    $reserved = AdminFasPurchaseRequisitionItemAccountable::where('admin_fas_purchase_requisition_item_id', $requisiton_item_id)->orderBy('id','desc')->get();
                    if($reserved->count() > 0){
                        foreach($reserved as $res){
                            $accountable_employee = $res->employee_id != null || $res->employee_id != '' ? $res->employee_id : null;
                            $accountable_quantity = $res->quantity;
                            for ($i = 1; $i <=  $accountable_quantity; $i++) {
                                $asset = AdminAssetsList::where('admin_purchase_order_item_id', $po_item->id)
                                ->where('accounted', 0)
                                ->where('printed', 0)
                                ->where('active', 1)
                                ->where('emp_id', null)
                                ->orderBy('id','asc')
                                ->first();
                                if($asset){
                                    $asset->emp_id = $accountable_employee ?? null;
                                    $asset->department_id = $res->department_id ?? null;
                                    $asset->save();
                                }
                            }
                        }
                    }
                }
            }
        }));
    }
}
