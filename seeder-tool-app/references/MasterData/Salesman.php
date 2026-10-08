<?php

namespace App\Models\MasterData;

use App\Models\Cluster;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sales\SalesmanPosition;
use App\Models\SalesmanCluster;
use App\Models\SalesmanMonthlyTarget;
use App\Models\SubCluster;
use App\Traits\Caching;
use OwenIt\Auditing\Contracts\Auditable;


class Salesman extends Model implements Auditable
{
    use SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'salesman';

    protected $fillable = [
        'code',
        'name',
        'user_id',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salesman_position() {
        return $this->belongsTo(SalesmanPosition::class);
    }

    public function clusters()
    {
        return $this->hasManyThrough(Cluster::class, SalesmanCluster::class, 'salesman_id', 'id', 'id', 'cluster_id')
            ->distinct('clusters.id');
    }

    public function sub_clusters()
    {
        return $this->hasManyThrough(SubCluster::class, SalesmanCluster::class, 'salesman_id', 'id', 'id', 'sub_cluster_id');
    }

    public function salesman_monthly_targets()
    {
        return $this->hasMany(SalesmanMonthlyTarget::class, 'salesman_id');
    }

    /**
     * Boot all of the bootable traits on the model.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->code = "INGCOSLSM" . date('jnGi');
        });

        $cache_names = [
            'salesman_dropdown',
            'salesmans_selection'
        ];

        static::created((function ($model) use($cache_names){
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names){
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use($cache_names){
            $model->forget($cache_names);
        }));
    }
}
