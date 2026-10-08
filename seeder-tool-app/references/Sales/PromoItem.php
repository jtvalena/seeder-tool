<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Items\Item;
use App\Models\Sales\Promo;

class PromoItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['item_model', 'item_name'];

    protected $fillable = [
        'promo_id',
        'item_id',
        'quantity_from',
        'quantity_to',
        'lowest_price',
        'cash_only',
    ];

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getItemNameAttribute(){
        $item_name = "";
        if(!empty($this->item)){
            $item_name = !empty($this->item) ? $this->item->name : '';
        }
        unset($this->item);
        
        return $item_name;
    }

    public function getItemModelAttribute(){
        $item_model = "";
        if(!empty($this->item)){
            $item_model = !empty($this->item) ? $this->item->model : '';
        }
        unset($this->item);
        
        return $item_model;
    }
}
