<?php

namespace App\Models;

use App\Models\MasterData\Customer;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerCluster extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'customer_id',
        'cluster_id',
        'sub_cluster_id',
    ];

    public function customer() {
        return $this->hasOne(Customer::class);
    }

    public function cluster() {
        return $this->hasOne(Cluster::class);
    }

    public function sub_cluster() {
        return $this->hasOne(SubCluster::class);
    }
}
