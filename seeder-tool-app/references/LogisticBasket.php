<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\LogisticBasketItem;

class LogisticBasket extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $appends = ['has_dispatch'];

    public function logistic_basket_item(){
        return $this->hasMany(LogisticBasketItem::class, 'logistic_basket_id');
    }

    public function getHasDispatchAttribute() {
        return $this->dispatch_id == null ? false : true;
    }
    public static function boot(){
        parent::boot();

        static::creating(function ($model) {
            $latest = LogisticBasket::orderBy('id','desc')->first();

            if (!empty($latest)) {
                $latest = (int)$latest->series;
                $next_lb_num = ($latest != null) ? intval($latest) + 1 : 1;
                $model->series = str_pad((string)$next_lb_num, 6, '0', STR_PAD_LEFT);
            } else {
                $model->series = str_pad('1', 6, '0', STR_PAD_LEFT);
            }

        });
    }
}
