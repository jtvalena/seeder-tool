<?php

namespace App\Models\Service;

// use App\Events\RealtimeServiceDashboardQueueing;
// use App\Events\RealtimeServiceQueueing;
// use App\Http\Controllers\WarehouseQueueingController;
use App\Models\Accounting\CreditMemo;
use App\Models\ScRfLatestMovement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Service\ScReceivingReport;
use App\Models\Service\ScRepairFormItem;
use App\Models\Service\ScRfTechnician;
use App\Models\User;
use App\Models\Service\ScRfEstimation;
use App\Models\Service\ScPartsOrder;
use App\Models\Service\ScRepairFormFile;
use App\Models\Service\ScRfMovement;
use App\Models\Service\ScDelivery;
use App\Models\Service\ScDrRepairForm;
use App\Models\Items\Item;
use App\Models\Sales\PackingList;
use App\Models\Sales\SalesOrder;
use App\Models\Service\ScTechnician;
use App\Models\Sales\SalesQuotation;
use App\Traits\Caching;
use App\Traits\HasAttributeChangesToBroadcast;
use App\Traits\MorphMap;
use Carbon\Carbon;
use stdClass;

class ScRepairForm extends Model implements Auditable
{
    use HasFactory, Caching;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use MorphMap,HasAttributeChangesToBroadcast;

    protected $fillable = [
        'rf_number',
        'status',
        'for_replacement',
        'sc_technician_id',
        'latest_date',
        'for_recovery',
        'crate_number',
        'rfd_date',
        'service_unit',
        'job_card_num',
        'packing_list_id',
    ];

    protected $appends = [
        'morphMapKey',
        'transmital',
        'assigned_technician',
        'sales_order_num',
        'packing_list_num',
    ];

    public function sc_receiving_report()
    {
        return $this->belongsTo(ScReceivingReport::class);
    }

    public function sc_repair_form_item()
    {
        return $this->hasOne(ScRepairFormItem::class, 'sc_repair_form_id');
    }

    public function sc_rf_technicians()
    {
        return $this->hasMany(ScRfTechnician::class, 'sc_repair_form_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sc_rf_estimations()
    {
        return $this->hasMany(ScRfEstimation::class, 'sc_repair_form_id');
    }

    public function sc_parts_orders()
    {
        return $this->hasMany(ScPartsOrder::class, 'sc_repair_form_id');
    }

    public function sc_dr_repair_forms()
    {
        return $this->hasMany(ScDrRepairForm::class, 'sc_repair_form_id');
    }

    public function sc_repair_form_files()
    {
        return $this->hasMany(ScRepairFormFile::class, 'sc_repair_form_id');
    }

    public function sc_rf_movements()
    {
        return $this->hasMany(ScRfMovement::class, 'sc_repair_form_id');
    }

    public function sc_delivery()
    {
        return $this->belongsTo(ScDelivery::class);
    }

    public function sc_technician()
    {
        return $this->belongsTo(ScTechnician::class);
    }

    public function item(){
        return $this->hasOne(Item::class, 'id');
    }

    public function sales_quotations()
    {
        return $this->hasMany(SalesQuotation::class, 'sc_repair_form_id');
    }

    public function sales_order()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
    
    public function sales_orders()
    {
        return $this->hasMany(SalesOrder::class, 'sc_repair_form_id');
    }

    public function packing_list()
    {
        return $this->belongsTo(PackingList::class, 'packing_list_id');
    }

    public function credit_memos()
    {
        return $this->hasMany(CreditMemo::class, 'sc_repair_form_id');
    }

    // ── Computed accessors for dropped columns ───────────────────────────────

    public function getAssignedTechnicianAttribute(): ?string
    {
        return $this->relationLoaded('sc_technician')
            ? ($this->sc_technician?->name)
            : null;
    }

    public function getSalesOrderNumAttribute(): ?string
    {
        return $this->relationLoaded('sales_order')
            ? ($this->sales_order?->order_num)
            : null;
    }

    public function getPackingListNumAttribute(): ?string
    {
        return $this->relationLoaded('packing_list')
            ? ($this->packing_list?->packing_list_num)
            : null;
    }

    public function getTransmitalAttribute(){

        $po = ScPartsOrder::where('sc_repair_form_id',$this->id)->orderBy('id','desc')->first();
        $obj = new stdClass;




        if(!!$po){
            $po_items = ScPartsOrderItem::where('sc_parts_order_id',$po->id)->first();
            if(!!$po_items){
                $obj->stock_request_id = $po_items->stock_request_id;
                $obj->stock_transfer_id = $po_items->stock_transfer_id;
            }
        }


        return $obj;
    }

    public static function boot()
    {
        parent::boot();

        $cache_names = [
            'sc_repair_form',
            'back_job_selection'
        ];

        static::creating(function($model) use($cache_names) {
            $latest = ScRepairForm::select('rf_number')->whereNotNull('rf_number')->whereRaw('length(rf_number)=7')->max('rf_number');
            $latest = str_replace('SC', '', $latest);
            if(!$model->defective){
                if (!empty($latest)) {
                    $next_rf_num = ($latest != null) ? intval($latest) + 1 : 1;
                    $model->rf_number = 'SC' . str_pad((string)$next_rf_num, 5, '0', STR_PAD_LEFT);
                } else {
                    $model->rf_number = 'SC' . str_pad('1', 5, '0', STR_PAD_LEFT);
                }
            }else{
                $latest = ScRepairForm::select('rf_number')->whereNotNull('rf_number')->whereRaw('length(rf_number)=7')->where('rf_number','like','%-D%')->max('rf_number');
                $next_rf_num = ($latest != null) ? intval($latest) + 1 : 1;
                $model->rf_number = str_pad((string)$next_rf_num, 5, '0', STR_PAD_LEFT). '-D';
            }
            $model->status = "RFC";
            $model->forget($cache_names);
        });

        static::created(function ($model) {
            ScRfMovement::insert([
                'sc_repair_form_id' => $model->id,
                'status' => $model->status,
                'updated_by_id' => \Auth::id(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if(!!$model->sc_repair_form_id){
                $rf = ScRepairForm::where('id',$model->sc_repair_form_id)->first();
                $has_record = 1;
                $counter = 1;
                while($has_record == 1){
                    $checker = ScRepairForm::where('rf_number',$rf->rf_number.'-'.$counter)->count();
                    $checker > 0 ? $counter += 1 : $has_record = 0;
                }
                $model->rf_number = $rf->rf_number.'-'.$counter.'-B';
                $model->update();
            }
        });

        // static::updating(function($model){
        //     if($model->isDirty('status')){
        //         $rfMovementLastRecord = ScRfMovement::where('sc_repair_form_id',$model->id)->orderBy('id','desc')->first();

        //         $rfMovement = new ScRfMovement;
        //         $rfMovement->sc_repair_form_id = $model->id;
        //         $rfMovement->status = $model->status;
        //         $rfMovement->updated_by_id =\Auth::id();

        //         $rfMovement->save();

        //         // ScRfLatestMovement::updateOrCreate([
        //         }
        //     }
        static::updated(function($model) use($cache_names){
            if($model->wasChanged('status')){
                $rfMovementLastRecord = ScRfMovement::where('sc_repair_form_id',$model->id)->orderBy('id','desc')->first();
                $rfMovement = new ScRfMovement;
                $rfMovement->sc_repair_form_id = $model->id;
                $rfMovement->status = $model->status;
                $rfMovement->updated_by_id = \Auth::id();

                $rfMovement->save();

                $column =  $model->status == 'QUO' ? 'quo_date' : 'pin_date';
                ScRepairForm::where('id',$model->id)
                    ->update([$column => now()]);

                if($model->status == 'RFD') {
                    ScRepairForm::where('id',$model->id)
                        ->update(['rfd_date' => now()]);
                }

                ScRepairForm::where('id', $model->id)->update([
                    'latest_date' => now(),
                ]);

                ScRfLatestMovement::updateOrCreate([
                    'sc_repair_form_id' => $model->id,
                    'status' => $model->status,
                ],
                [
                    'sc_repair_form_id' => $model->id,
                    'days' => date_diff(date_create($rfMovementLastRecord->created_at),now(),"%a")->format("%a"),
                    'status' => $model->status,
                    'sc_rf_movement_id'=> $rfMovementLastRecord->id,
                ]);
                // broadcast(new RealtimeServiceDashboardQueueing(WarehouseQueueingController::getAllQueueAfterSalesDashboard()));
                // broadcast(new RealtimeServiceQueueing(WarehouseQueueingController::queueAfterSales('rf_status',null,null)));
            }
            $model->forget($cache_names);
        });
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }

}
