<?php
namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Items\Item;
use App\Models\Operations\CheckerForm;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sales\SalesOrderItem;

class CheckerFormItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

   // protected $appends = ['stock'];

    protected $fillable = [
        'checker_form_id',
        'wp_list_item_id',
        'quantity_served',
        'quantity',
        'item_id',
        'sales_order_item_id',
        'sc_quantity',
        'bundle_group_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function wp_list_item()
    {
        return $this->belongsTo(WarehousePreparationListItem::class);
    }

    public function checker_form()
    {
        return $this->belongsTo(CheckerForm::class, 'checker_form_id');
    }

    public function sales_order_item()
    {
        return $this->belongsTo(SalesOrderItem::class, 'sales_order_item_id');
    }
}
