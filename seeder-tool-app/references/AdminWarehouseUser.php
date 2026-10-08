<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdminWarehouse;
use App\Models\User;

class AdminWarehouseUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_warehouse_id',
    ];

     public function adminWarehouse(){
        return $this->hasMany(AdminWarehouse::class,'admin_warehouse_id');
    }

    public function user(){
        return $this->hasMany(User::class,'user_id');
    }
}
