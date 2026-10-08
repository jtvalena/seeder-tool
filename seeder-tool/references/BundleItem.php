<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Items\Item;

class BundleItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $appends = ['item_name' , 'item_model', 'item_selling_price'];

    protected $fillable = [
        'bundle_id',
        'item_id',
        'quantity',
        'foc',
        'total_amount',
        'override_amount',
        'moq',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getItemNameAttribute(){
        $item_name = "";
        if(!empty($this->item)){
            $item_name = !empty($this->item) ? $this->item->name : '';
        }
        //unset($this->item);

        return $item_name;
    }

    public function getItemModelAttribute(){
        $item_model = "";
        if(!empty($this->item)){
            $item_model = !empty($this->item) ? $this->item->model : '';
        }
        //unset($this->item);

        return $item_model;
    }

    public function getItemSellingPriceAttribute(){
        $item_price = "";
        if(!empty($this->item)){
            $item_price = !empty($this->item) ? $this->item->selling_price : 0;
        }
        //unset($this->item);

        return $item_price;
    }
}
