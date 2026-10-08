<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\ScReceivingReport;
use App\Traits\Caching;

class ScCustomer extends Model implements Auditable
{
    use HasFactory, Caching;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'contact_number',
    ];

    public function sc_receiving_report() {
        return $this->belongsTo(ScReceivingReport::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->forget('service_end_user_customer');
        });

        static::updated((function ($model) {
            $model->forget('service_end_user_customer');
        }));

        static::deleted((function ($model) {
            $model->forget('service_end_user_customer');
        }));
    }
}
