<?php
namespace App\Models\Items;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ItemType extends Model implements Auditable
{
    use SoftDeletes, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'item_types';

    protected $fillable = [
        'code',
        'name',
        'active',
        'discount_field',
        'is_standard',
        'notify_on_changes',
        'use_sp_price',
    ];


    /**
     * Boot all of the bootable traits on the model.
     */
    public static function boot()
    {
        parent::boot();

        static::created((function ($model) {
            $model->forget('type_dropdown');
        }));
        static::updated((function ($model) {
            $model->forget('type_dropdown');
        }));
        static::deleted((function ($model) {
            $model->forget('type_dropdown');
        }));
    }
}
