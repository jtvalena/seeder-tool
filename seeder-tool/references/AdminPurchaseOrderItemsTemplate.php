<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\Caching;

class AdminPurchaseOrderItemsTemplate extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'admin_purchase_order_items_templates';

    protected $fillable = [
        'code',
        'name',
    ];

    public function item_list(){
        return $this->hasMany(AdminPurchaseOrderItemsTemplateDetails::class,'template_id');
    }
}
