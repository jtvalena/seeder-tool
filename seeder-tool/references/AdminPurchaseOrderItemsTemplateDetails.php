<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\Caching;

class AdminPurchaseOrderItemsTemplateDetails extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'admin_purchase_order_items_template_details';

    protected $fillable = [
        'template_id',
        'admin_item_id',
        'admin_item_name',
        'quantity',
        'admin_item_uom_id',
        'moq',
    ];

    public function admin_purchase_order_items_templates(){
        return $this->belongsTo(AdminPurchaseOrderItemsTemplate::class,'id');
    }

}
