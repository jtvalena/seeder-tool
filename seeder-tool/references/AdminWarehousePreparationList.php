<?php

namespace App\Models;

use App\Http\Controllers\SharedFunctionController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AdminWarehousePreparationList extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_wpf_num',
        'status',
        'admin_location_id',
        'description',
        'prepared_by',
        'approved_by',
        'approved_date',
        'updated_by',
    ];

    public function admin_wpf_items(){
        return $this->hasMany(AdminWarehousePreparationListItem::class,'admin_warehouse_preparation_list_id');
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            //trunsaction number
            DB::transaction(function () use ($model) {
                $currentYear = date('Y');
                $previous_admin_wpf_num = AdminWarehousePreparationList::whereNotNull('admin_warehouse_preparation_lists.admin_wpf_num')
                    ->where('admin_warehouse_preparation_lists.admin_wpf_num', 'REGEXP', '^' . $currentYear . '-\d{4}$')
                    ->orderBy('admin_warehouse_preparation_lists.id', 'desc')
                    ->pluck('admin_warehouse_preparation_lists.admin_wpf_num')
                    ->first();
            
                // Generate the new padded number
                $paddedNum = SharedFunctionController::padForSeries(AdminWarehousePreparationList::class, $model->id, 4, $previous_admin_wpf_num);
            
                // Update the model with the unique admin_wpf_num
                $model->update(['admin_wpf_num' => $model['admin_wpf_num'] ? $model['admin_wpf_num'] : $paddedNum]);
            });

            //Inbound Outbound creation 
            // $data = request();
            // $inbound_outbound_type_id = InboundOutboundType::where('module', 'adminTurnOver')->first();
            // $type = request()->get('type');
            // $inboundOutbound = new AdminInboundOutbound();
            // $inboundOutbound->transaction_number = $model->admin_wpf_num;
            // $inboundOutbound->transact_id = $model->id;
            // $inboundOutbound->inbound_outbound_type_id = $inbound_outbound_type_id->id;
            // $inboundOutbound->warehouse_id = isset($data['warehouse_id'])?$data['warehouse_id']:0;
            // $inboundOutbound->remarks = isset($data['remarks'])?$data['remarks']:'';
            // $inboundOutbound->created_by = Auth::user()->id;
            // $inboundOutbound->type = 1;
            // $inboundOutbound->save();
        });
     
    }
}
