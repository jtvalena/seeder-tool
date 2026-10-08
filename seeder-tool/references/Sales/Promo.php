<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\CustomerPromo;
use App\Models\Sales\PromoItem;
use App\Models\User;

class Promo extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['user_name'];

    protected $fillable = [
        'code',
        'name',
        'date_start',
        'date_end',
        'user_id',
        'active',
        'exclusive',
        'quantity_type',
        'auto_add_new_customer',
        'for_all_customer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer_promos()
    {
        return $this->hasMany(CustomerPromo::class, 'promo_id');
    }

    public function promo_items()
    {
        return $this->hasMany(PromoItem::class, 'promo_id');
    }

    public function getUserNameAttribute(){
        $user_name = !empty($this->user) ? $this->user->name : '';
        unset($this->user);
        return $user_name;
    }
}
