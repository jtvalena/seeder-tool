<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminAssetTurnoverItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'admin_asset_turnover_id',
        'admin_accountability_id',
        'admin_accountability_item_id',
        'admin_assets_list_id',
        'admin_asset_condition_id',
        'warehouse_id',
        'account_to_employee_data_id',
        'depreciated_life',
        'amount',
        'employee_shoulder',
        'company_shoulder',
        'billable',
        'atd_status',
    ];


    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
                $inboundOutbound = AdminInboundOutbound::orderBy('id','desc')->first();
                $inboundOutboundItem = new AdminInboundOutboundItems();
                $inboundOutboundItem->admin_inbound_outbound_id = $inboundOutbound->id;
                $inboundOutboundItem->transact_item_id = $model->id;
                $inboundOutboundItem->in_qty = 1;
                $inboundOutboundItem->save();
        });
     
    }
}
