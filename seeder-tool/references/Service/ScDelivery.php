<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Service\ScRepairForm;
use App\Models\Service\ScReceivingReport;
use App\Models\MasterData\Customer;
use App\Models\Vehicle;
use App\Models\Service\ScCustomer;

class ScDelivery extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'dr_number'
    ];

    public function sc_dr_repair_forms()
    {
        return $this->hasMany(ScDrRepairForm::class, 'sc_delivery_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function sc_receiving_report() {
        return $this->belongsTo(ScReceivingReport::class);
    }

    public function sc_repair_forms()
    {
        return $this->hasMany(ScRepairForm::class, 'sc_delivery_id');
    }

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

    public function sc_customer()
    {
        return $this->hasOne(ScCustomer::class,'id', 'sc_customer_id');
    }

    public function sc_location()
    {
        return $this->hasOne(ScLocation::class,'id', 'sc_location_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = ScDelivery::select('dr_number')->whereRaw('length(dr_number)=6')->max('dr_number');
            if (!empty($latest)) {
                $next_rf_num = ($latest != null) ? intval($latest) + 1 : 1;
                $model->dr_number = str_pad((string)$next_rf_num, 6, '0', STR_PAD_LEFT);
            } else {
                $model->dr_number = str_pad('1', 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
