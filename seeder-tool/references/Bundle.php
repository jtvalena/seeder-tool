<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\BundleItem;
use App\Models\Items\Item;
use App\Traits\Caching;

class Bundle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

    protected $appends = ['item_name' , 'item_model', 'group_text'];

    protected $fillable = [
        'id',
        'item_id',
        'active',
        'group'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function bundle_items()
    {
        return $this->hasMany(BundleItem::class);
    }

    public function bundle_customer()
    {
        return $this->hasMany(BundleCustomer::class);
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

    public function getGroupTextAttribute()
    {
        if($this->group==0){
            return 'Auto Add';
        }else if($this->group==1){
            return 'Single';
        }else if($this->group==2){
            return 'Multiple';
        }
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'bundle_dropdown',
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
