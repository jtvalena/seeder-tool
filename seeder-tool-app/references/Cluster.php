<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Cluster extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'code',
        'name',
    ];

    public function sub_clusters() {
        return $this->hasMany(SubCluster::class);
    }

    public function customers()
    {
        return $this->hasMany(CustomerCluster::class);
    }

    public function salesman()
    {
        return $this->hasMany(SalesmanCluster::class);
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'cluster_dropdown',
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
