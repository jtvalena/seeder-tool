<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminFasBasket extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'ref_num',
        // 'company_id',
        // 'store_branches_id',
        'department_id',
        'location_id',
        'prepared_by',
        'updated_by',
        'status',
        'total_amount',
        'reject_reason',
        'remarks',
        'expected_date',
        'basket_type',
        'type',
        'capital_vendor_id',
        'adjusted_by',
        'adjusted_date',
    ];
    protected $appends = ['fas_id','fas_id_store'];

    protected $ho_arr = [];
    protected $store_arr = [];

    public function fasId(){
        return $this->hasMany(AdminFasSelected::class,'admin_fas_basket_id');
    }

    public function getFasIdAttribute(){
        
        $this->fasId()
        ->join('admin_fas_purchase_requisitions','admin_fas_purchase_requisitions.id','admin_fas_selecteds.admin_fas_requisition_id')
        ->where('type','ho')->get()->each(function($query){
            array_push($this->ho_arr,$query->admin_fas_requisition_id);
        });

        return $this->ho_arr;
    }


    public function getFasIdStoreAttribute(){
        
        $this->fasId()
        ->join('admin_fas_purchase_requisitions','admin_fas_purchase_requisitions.id','admin_fas_selecteds.admin_fas_requisition_id')
        ->where('type','stores')->get()->each(function($query){
            array_push($this->store_arr,$query->admin_fas_requisition_id);
        });

        return $this->store_arr;
    }


}
