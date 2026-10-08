<?php

namespace App\Models;

// use App\Http\Controllers\SharedFunctionController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminAssetTurnover extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'admin_location_id',
        'department_id',
        'employee_data_id',
        'employee_data_ids',
        'ldrf',
        'repairable',
        'purpose',
        'audit_findings',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'account_to_employee_data_id',
        'approved_by',
        'approved_date',
        'turn_over_num',
        'admin_asset_ldrf_type_id',
        'others_text',
        'ldrf_reason',
        'it_conclusion',
        'to_department_id',
        'to_system_id',
    ];

 /**
     * Boot all of the bootable traits on the model.
     */
    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            //trunsaction number
            DB::transaction(function () use ($model) {
                
                $getDepartment =  Department::find($model->department_id);
                $code = $getDepartment->admin_code ?? 'N/A';
                $currentYear = date('Y');
                $previous_turn_over_num = AdminAssetTurnover::whereNotNull('admin_asset_turnovers.turn_over_num')
                ->where('turn_over_num', 'REGEXP', "^{$code}-{$currentYear}-\d{3}$")
                ->where('ldrf',$model->ldrf)
                ->orderBy('admin_asset_turnovers.id', 'desc')
                ->lockForUpdate() // Apply the lock
                ->pluck('admin_asset_turnovers.turn_over_num')
                ->first();

                if ($previous_turn_over_num) {
                    $newNumber = str_pad(((int) substr($previous_turn_over_num, -3)) + 1, 3, '0', STR_PAD_LEFT);
                    $paddedNum = preg_replace('/\d{3}$/', $newNumber, $previous_turn_over_num);
                } else {
                    // Default first entry
                    $paddedNum = "{$code}-{$currentYear}-001";
                }
            
                // // Generate the new padded number
                // $paddedNum = SharedFunctionController::padForSeries(AdminAssetTurnover::class, $model->id, 3, $previous_turn_over_num);
            
                // Ensure uniqueness
                // if (AdminAssetTurnover::where('turn_over_num', $paddedNum)->exists()) {
                //     throw new \Exception("Turn over number duplication detected.");
                // }
            
                // Update the model with the unique turn_over_num
                $model->update(['turn_over_num' => $model['turn_over_num'] ? $model['turn_over_num'] : $paddedNum]);
            });

            //Inbound Outbound creation 
            if($model->account_to_employee_data_id == null && $model->ldrf == 0) {
                $data = request();
                $inbound_outbound_type_id = InboundOutboundType::where('module', 'adminTurnOver')->first();
                $type = request()->get('type');
                $inboundOutbound = new AdminInboundOutbound();
                $inboundOutbound->transaction_number = $model->turn_over_num;
                $inboundOutbound->transact_id = $model->id;
                $inboundOutbound->inbound_outbound_type_id = $inbound_outbound_type_id->id;
                $inboundOutbound->warehouse_id = isset($data['warehouse_id'])?$data['warehouse_id']:0;
                $inboundOutbound->remarks = isset($data['remarks'])?$data['remarks']:'';
                $inboundOutbound->created_by = Auth::user()->id;
                $inboundOutbound->type = 1;
                $inboundOutbound->save();
            }
        });
    }
}
