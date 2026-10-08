<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SalesmanCluster extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'salesman_id',
        'cluster_id',
        'sub_cluster_id',
    ];

    public function salesman() {
        return $this->belongsTo(Salesman::class);
    }

    public function cluster() {
        return $this->belongsTo(Cluster::class);
    }

    public function sub_cluster() {
        return $this->belongsTo(SubCluster::class);
    }
}
