<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Service\ScCustomer;
use App\Models\MasterData\Customer;
use App\Models\Service\ScLocation;
use App\Traits\Caching;

class ScReceivingReport extends Model implements Auditable
{
    use HasFactory, Caching;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'rr_number',
        'status'
    ];

    public function sc_customer(){
        return $this->hasOne(ScCustomer::class, 'sc_receiving_report_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function sc_repair_forms(){
        return $this->hasMany(ScRepairForm::class, 'sc_receiving_report_id');
    }

    public function sc_deliveries(){
        return $this->hasMany(ScDelivery::class, 'sc_receiving_report_id');
    }

    public function sc_location(){
        return $this->hasOne(ScLocation::class, 'id', 'sc_location_id');
    }

    public static function boot() {
        parent::boot();

        $cache_names = [
            'service_end_user_customer',
        ];
        static::created((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));

        static::creating(function ($model) {
            $latest = ScReceivingReport::select('rr_number')->whereRaw('length(rr_number)=6')->max('rr_number');
            if(!empty($latest)) {
                $next_rr_num = ($latest != null) ? intval($latest) + 1: 1;
                $model->rr_number = str_pad((string)$next_rr_num, 6, '0', STR_PAD_LEFT);
            } else {
                $model->rr_number = str_pad('1', 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
