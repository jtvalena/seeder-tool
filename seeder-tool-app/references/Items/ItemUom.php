<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ItemUom extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'item_uom';

    protected $fillable = [
        'code',
        'description',
        'name',

    ];

    /**
     * Boot all of the bootable traits on the model.
     */
    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->code = "INGCOUOM" . date('jnGi');
    //     });
    // }
}
