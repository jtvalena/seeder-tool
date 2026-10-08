<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class StoreUser extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'user_id',
        'parent_id',
        'handle_type',
        'super_user',
    ];

    public function store_user_clusters()
    {
        return $this->hasMany(StoreUserCluster::class);
    }

    public function store_user_customers()
    {
        return $this->hasMany(StoreUserCustomer::class);
    }
}
