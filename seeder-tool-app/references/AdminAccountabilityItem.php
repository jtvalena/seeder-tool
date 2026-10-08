<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\HasAttributeChangesToBroadcast;
use App\Traits\MorphMap;

class AdminAccountabilityItem extends Model implements Auditable
{
    
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable,MorphMap,HasAttributeChangesToBroadcast;
    protected $appends = ['morphMapKey'];
    protected $table = 'admin_accountability_items';

    protected $fillable = [
        'admin_assets_list_id',
        'admin_accountability_id',
        'previous_admin_accountability_id',
        'admin_category_id',
        'item_name',
        'remarks',
        'status',
        'company_id',
        'admin_asset_condition_id',
        'item_id',
        'employee_id',
        'vendor_id',
        'warehouse_item_id',
        'admin_asset_turnover_item_id',
        'printed',
        'borrowed',
    ];



    public static function boot()
    {
        parent::boot();
        // static::created(function ($model) {
        //         $inboundOutbound = AdminInboundOutbound::orderBy('id','desc')->first();
        //         $inboundOutboundItem = new AdminInboundOutboundItems();
        //         $inboundOutboundItem->admin_inbound_outbound_id = $inboundOutbound->id;
        //         $inboundOutboundItem->transact_item_id = $model->id;
        //         $inboundOutboundItem->save();
        // });
    }

}
