<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerTempCluster extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'customer_temp_id',
        'cluster_id',      
        'sub_cluster_id',  
    ];

    public function customer_temp() {
        return $this->hasOne(CustomerTemp::class);
    }

    public function cluster() {
        return $this->hasOne(Cluster::class);
    }

    public function sub_cluster() {
        return $this->hasOne(SubCluster::class);
    }
}
