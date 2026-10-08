<?php

namespace App\Models\Operations;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'warehouse_id',
    ];

    public function warehouse():HasMany {
        return $this->hasMany(Warehouse::class,'warehouse_id');
    }

    public function user():HasMany{
        return $this->hasMany(User::class,'user_id');
    }
}
