<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use App\Models\Items\Item;
use App\Models\Operations\Warehouse;
// use App\Models\Service\ScRepairForm;
// use App\Models\Service\ScRfEstimation;
// use App\Models\Service\ScRfEstItem;
// use Illuminate\Support\Facades\DB;

class WarehouseItemStocks extends Model
{
    protected $appends = ['warehouse_name', 'warehouse_code'];

    protected $fillable = [
        'warehouse_id',
        'item_id',
        'quantity',
        'item_location',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getWarehouseNameAttribute(){
        $warehouse_name = $this->warehouse->name ?? '';
        unset($this->warehouse);
        return $warehouse_name;
    }

    public function getWarehouseCodeAttribute(){
        $warehouse_code = $this->warehouse->code ?? '';
        unset($this->warehouse);
        return $warehouse_code;
    }

    public function scopeWithStocks($query, $selectedWarehouses)
    {
        if(!empty($selectedWarehouses)){
            return $query->groupBy('warehouse_item_stocks.item_id')
                    ->havingRaw('SUM(warehouse_item_stocks.quantity) > 0')
                    ->whereIn('warehouse_item_stocks.warehouse_id', $selectedWarehouses);
        }else{
            return $query->groupBy('warehouse_item_stocks.item_id')->havingRaw('SUM(warehouse_item_stocks.quantity) > 0');
        }
    }

    public function scopeWithOutStocks($query, $selectedWarehouses)
    {
        if(!empty($selectedWarehouses)){
            return $query->groupBy('warehouse_item_stocks.item_id')
                    ->havingRaw('SUM(warehouse_item_stocks.quantity) = 0')
                    ->whereIn('warehouse_item_stocks.warehouse_id', $selectedWarehouses);
        }else{
            return $query->groupBy('warehouse_item_stocks.item_id')->havingRaw('SUM(warehouse_item_stocks.quantity) = 0');
        }
    }

    public static function boot(){
        parent::boot();

        // static::updating(function($model){
        //     $warehouse = Warehouse::find($model->warehouse_id);
        //     if($warehouse->service == 1){
        //         ScRfEstimation::join('sc_repair_forms','sc_rf_estimations.sc_repair_form_id','sc_repair_forms.id')
        //             ->join('sc_rf_est_items','sc_rf_est_items.sc_rf_estimation_id','sc_rf_estimations.id')
        //             ->where('sc_rf_est_items.item_id',$model->item_id)
        //             ->where('sc_repair_forms.status','PP')->groupby('sc_repair_forms.id')->select(DB::raw('max(sc_rf_estimations.id) as id'),'sc_rf_estimations.sc_repair_form_id')
        //             ->chunk(200,function($est) use($model){
        //                 foreach($est as $a){
        //                     ScRfEstItem::where('sc_rf_estimation_id',$a->id)->where('item_id',$model->item_id)->update(['stock' => $model->quantity]);
        //                     $m = ScRfEstItem::where('sc_rf_estimation_id',$a->id)->get();
        //                     $unservable = 0;

        //                     foreach($m as $i){
        //                         if($i->stock < $i->requested_qty){
        //                             $unservable++;
        //                         }
        //                     }

        //                     if($unservable == 0){
        //                         ScRepairForm::where('id',$a->sc_repair_form_id)->update(['ready_to_proceed'=>1]);
        //                     }else{
        //                         ScRepairForm::where('id',$a->sc_repair_form_id)->update(['ready_to_proceed'=>0]);
        //                     }
        //                 }
        //             });
        //     }
        // });
    }
}
