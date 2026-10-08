<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class SubCluster extends Model implements Auditable
{
    use HasFactory, Caching;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'cluster_id',
        'code',
        'name',
    ];

    public function cluster() {
        return $this->belongsTo(Cluster::class);
    }

    public function customers()
    {
        return $this->hasMany(CustomerCluster::class);
    }

    public function salesmans()
    {
        return $this->hasMany(SalesmanCluster::class);
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'sub_cluster_selection',
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
}
