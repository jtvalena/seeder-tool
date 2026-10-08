<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class StoreUserCluster extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'store_user_id',
        'cluster_id',
        'sub_cluster_id',
    ];

    public function store_user()
    {
        return $this->belongsTo(StoreUser::class);
    }
}
