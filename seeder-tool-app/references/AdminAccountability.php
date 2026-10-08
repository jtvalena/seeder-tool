<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\HasAttributeChangesToBroadcast;
use App\Traits\MorphMap;
// use Illuminate\Support\Facades\Auth;

class AdminAccountability extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable,MorphMap,HasAttributeChangesToBroadcast;
    protected $table = 'admin_accountability';
    protected $appends = ['morphMapKey','assignatory'];

    protected $fillable = [
        'accountability_num',
        'emp_id',
        'location_id',
        'department_id',
        'prepared_by',
        'checked_by',
        'noted_by',
        'updated_by',
        'company_id',
        'purpose',
        'audit_findings',
        'item_category',
        'admin_category_id',
        'printed',
        'warehouse_id',
        'filed',
        'filed_date',
        'ref_doc',
        'borrowed',
    ];

    public function admin_accountabilityitem(){
        return $this->hasMany(AdminAccountabilityItem::class,'admin_accountability_id');
    }

    public function getAssignatoryAttribute(){
        return ModuleSignatory::where('model_name','AdminAccountability')->first();
    }

    public function preparer(){
        return $this->belongsTo(User::class, 'user_id','id');
    }
   /**
     * Boot all of the bootable traits on the model.
     */
    public static function boot()
    {
        parent::boot();
        // static::created(function ($model) {
        //     $data = request()->get('form');
        //     $inbound_outbound_type_id = request()->get('inbound_outbound_type_id');
        //     $type = request()->get('type');
        //     $inboundOutbound = new AdminInboundOutbound();
        //     $inboundOutbound->transaction_number = $model->accountability_num;
        //     $inboundOutbound->transact_id = $model->id;
        //     $inboundOutbound->inbound_outbound_type_id = $inbound_outbound_type_id;
        //     $inboundOutbound->warehouse_id = isset($data['warehouse_id'])?$data['warehouse_id']:0;
        //     $inboundOutbound->created_by = Auth::user()->id ?? 0;
        //     $inboundOutbound->type = 1;
        //     $inboundOutbound->save();
        // });

    }
}
