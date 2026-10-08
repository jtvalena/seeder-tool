<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminRepairRequestForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'jo_number',
        'rrf_id',
        'vendor_id',
        'client_contact_person',
        'client_contact',
        'vendor_payment_item_id',
        'date_needed',
        'work_duration',
        'date_start',
        'date_finish',
        'total_material_cost',
        'total_cost',
        'remarks',
        'requested_by',
        'approve_admin_by',
        'endorsed_by',
        'processed_by',
        'noted_by',
        'approved_by',
        'updated_by',
        'deleted_by',
        'status',
        'payment_id',
        'vendor_address_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'paid',
        'department_id',
        'admin_location_id',
        'repair_category',
        'repair_type_id',
        'company_id',
        'store_branch_id',
        'vehicle_id',
        'payment_method',
        'total_service_amount',
        'total_amount',
        'total_paid_amount',
        'other_repair_category',
        'other_repair_type',
        'accomplished',
        'billable',
        'customer_request',
        'customer_id',
        'soa_id',
        'requestor',
        'balance_amount',
        'amount_type',
        'received',
        'received_by',
        'received_date',
    ];

    public function admin_repair_request_form_items(){
        return $this->hasMany(AdminRepairRequestFormItem::class,'arrf_id');
    }

    public function check_has_jo(){
        return $this->hasMany(AdminRepairRequestFormItem::class,'arrf_id')->where('jo_created', 1);
    }

    public static function boot()
    {
        parent::boot();
        static::created((function ($model){
            $company = Company::find($model->company_id);
            $model->system_id = !empty($company) ? $company->system_id : null;
            $model->save();
        }));
    }
}
