<?php

namespace App\Models;

use App\Models\Items\Item;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ReturnFormItems extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id',
        'item_id',
        'return_qty',
        'qr_code_base64',
        'return_form_id'
    ];
    protected $appends = [
        'item_model',
        'item_name',
        'item_uom',
        'item_categories',
        'item_types'
    ];

    public function return_form()
    {
        return $this->belongsTo(ReturnForm::class, 'id');
    }
    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
    public function getItemModelAttribute()
    {
        return $this->items->model ?? '';
    }

    public function getItemNameAttribute()
    {
        return $this->items->name ?? '';
    }

    public function getItemUomAttribute()
    {
        return $this->items->item_uom_text ?? '';
    }
    public function getItemCategoriesAttribute()
    {
        return $this->items->item_category_text ?? '';
    }
    public function getItemTypesAttribute()
    {
        return $this->items->item_type_text ?? '';
    }

}
