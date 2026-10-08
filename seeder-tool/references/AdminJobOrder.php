<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminJobOrder extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];
    protected $appends = ['transaction_number'];

    public function admin_job_order_items(){
        return $this->hasMany(AdminJobOrderItems::class,'job_order_ids');
    }
    public function getTransactionNumberAttribute()
    {
        return $this->jo_number;
    }
    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'admin_job_order',
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
    }
    public function tableNameForUpload()
    {
        return 'AdminJobOrderFile';
    }
}
