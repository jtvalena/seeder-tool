<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminInboundOutbound extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'transaction_number',
        'transact_id',
        'inbound_outbound_type_id',
        'type',
        'remarks',
        'status',
        'customer_id',
        'location_id',
        'department_id',
        'warehouse_id'
    ];
    protected $appends = ['assignatory'];

    public function getAssignatoryAttribute(){
        return ModuleSignatory::where('model_name','AdminInboundOutbound')->first()->signatory_name;
    }

    public function preparer(){
        return $this->belongsTo(User::class, 'created_by','id');
    }

}
