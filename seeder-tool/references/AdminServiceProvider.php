<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminServiceProvider extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'recurring_bill_type_id',
        'code',
        'name',
        'vendor_id',
    ];
    public function admin_service_provider_items()
    {
        return $this->hasMany(AdminServiceProviderItem::class,'admin_service_provider_id')->orderBy('id');
    }
}
