<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Items\Item;
use App\Models\Sales\SalesQuotation;
use App\Models\User;
use App\Models\Service\ScRfEstItem;
use OwenIt\Auditing\Contracts\Auditable;

class SalesQuotationItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['final_price','original_item','item_by_name', 'view_item'];

    protected $fillable = [
        'sales_quotation_id',
        'item_id',
        'quantity',
        'srp',
        'disc_price',
        'total_amount',
        'foc',
        'override_amount',
        'is_bundle',
        'promo_items_id',
        'amount',
        'backend_promo',
        'manual_foc',
        'bundle_group_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function item_substitute()
    {
        return $this->belongsTo(Item::class,'original_item_id');
    }

    public function sales_quotation()
    {
        return $this->belongsTo(SalesQuotation::class);
    }

    public function override_user()
    {
        return $this->belongsTo(User::class);
    }

    public function promo_item()
    {
        return $this->belongsTo(PromoItem::class, 'promo_items_id')->withTrashed();
    }
    public function sc_rf_est_item() {
        return $this->belongsTo(ScRfEstItem::class, 'id');
    }

    public function getFinalPriceAttribute(){
        if(!empty($this->override_amount)){
            return $this->override_amount;
        }else{
            return $this->amount;
        }
    }

    public function getViewItemAttribute(){
        if ($this->relationLoaded('item')) {
            return $this->getRelation('item');
        }
        return Item::select('id', 'model', 'name', 'type_id', 'uom_id', 'category_id', 'selling_price', 'sp_price', 'part_num')->where('id', $this->item_id)->first();
    }

    public function getOriginalItemAttribute(){
        if ($this->relationLoaded('item_substitute')) {
            return $this->getRelation('item_substitute');
        }
        return Item::select('id', 'model', 'name', 'type_id', 'uom_id', 'category_id', 'selling_price', 'sp_price', 'part_num', 'foc')->where('id', $this->original_item_id)->first();
    }

    public function getItemByNameAttribute(){
        $item_name = "";
        if(!empty($this->item_id)){
            $item_name = !empty($this->item) ? $this->item->model : '';
        }
        unset($this->item);

        return $item_name;
    }
}
