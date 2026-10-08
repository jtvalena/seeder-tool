<?php

namespace App\Models;

use App\Models\Items\Item;
use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SampleFormItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    protected $fillable = [
        'item_id',
        'borrowed_qty',
        'qr_code_base64',
        'id',
        'sample_form_id'
    ];
    protected $appends = [
        'item_model',
        'item_name',
        'item_uom',
        'item_categories',
        'item_types',
        'remaining_qty'
    ];

    public function sample_form()
    {
        return $this->belongsTo(SampleForm::class, 'id');
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
    public function getRemainingQtyAttribute()
    {
        $sf_id = $this->sample_form_id;
        $item_id = $this->item_id;
        $sf_details = ReturnForm::getQuery()
            ->join('return_form_items','return_form_items.return_form_id','return_forms.id')
            ->where('return_forms.sample_form_id',$sf_id)
            ->where('return_form_items.item_id',$item_id)
            ->where('return_forms.status',2)
            ->sum('return_qty');
        // If may partial na status sa return form
        if(!is_null($sf_details)){
            return $this->borrowed_qty - $sf_details;
        }
        else{
            return $this->borrowed_qty;
        }
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'sample_form_selection',
        ];
        static::created((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }

}
