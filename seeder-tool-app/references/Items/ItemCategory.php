<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ItemCategory extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'item_categories';

    protected $fillable = [
        'code',
        'name',
        'description',
        'active',
        'exclude_in_item_export',
        'auto_deactivate_on_zero_stocks',
        'disable_sp_price',
    ];

    /**
     * Boot all of the bootable traits on the model.
     */
    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->code = "INGCOITEMCAT" . date('jnGi');
    //     });
    // }
}
