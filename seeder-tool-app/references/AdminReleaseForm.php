<?php

namespace App\Models;

// use App\Http\Controllers\SharedFunctionController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class AdminReleaseForm extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'admin_release_forms';
    protected $fillable = [
        'admin_accountability_id',
        'accountability_num',
        'arf_num',
        'previous_accountability_num',
        'date_prepared',
        'time_delivered',
        'vehicle_id',
        'driver_name',
        'contact_number',
        'contact_person',
        'company_id',
        'store_branch_id',
        'sdr_number',
    ];
    protected $appends = ['assignatory'];

    public function getAssignatoryAttribute(){
        return ModuleSignatory::where('model_name','AdminReleaseForm')->first()->signatory_name;
    }


    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $data = request()->get('form');
            $inbound_outbound_type_id = request()->get('inbound_outbound_type_id');
            $type = request()->get('type');
            $filter = request()->get('filter');
            $findInboundType = InboundOutboundType::where('id',$inbound_outbound_type_id)->first();
            $getWarehouseFromLocation = AdminLocation::find(request()->get('location_id'));
            if($getWarehouseFromLocation){
                $data['warehouse_id'] = $getWarehouseFromLocation->admin_warehouse_id;
            }
            $arf_num = AdminReleaseForm::class;
            $id = AdminReleaseForm::orderBy('admin_release_forms.id', 'desc')->pluck('admin_release_forms.id')->first();
            $previous_arf_num = AdminReleaseForm::orderBy('admin_release_forms.id', 'desc')->pluck('admin_release_forms.arf_num')->first();
            // $paddedNum = SharedFunctionController::padForSeries($arf_num,  $id, 6, $previous_arf_num);
            if($findInboundType->module != 'AdminFasBasketOut'){
                $inboundOutbound = new AdminInboundOutbound();
                $inboundOutbound->transaction_number = request()->get('arf_num') ? request()->get('arf_num') : $previous_arf_num;
                $inboundOutbound->transact_id = $model->id;
                $inboundOutbound->inbound_outbound_type_id = $inbound_outbound_type_id;
                $inboundOutbound->warehouse_id = isset($data['warehouse_id'])?$data['warehouse_id']: (request()->get('admin_warehouse_id') ?? 0);
                $inboundOutbound->remarks = request()->get('remarks') ? request()->get('remarks'):'';
                $inboundOutbound->customer_id = isset($data['customer_id']) ? $data['customer_id']:'';
                $inboundOutbound->created_by = Auth::user()->id;
                $inboundOutbound->type = 2;
                $inboundOutbound->deliver_to = isset($filter['deliver_to']) ? $filter['deliver_to'] : null;
                $inboundOutbound->deliver_to_address = isset($filter['deliver_to_address']) ? $filter['deliver_to_address'] : null;
                $inboundOutbound->creator_department_id = Auth::user()->department_id ?? 1; 
                $inboundOutbound->save();
            }
            
        });
     
    }
}
