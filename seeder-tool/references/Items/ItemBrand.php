<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ItemBrand extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'item_brands';

    protected $fillable = [
        'code',
        'name',
        'active',
        'is_default',
        'company_id',
        'store_branch_id',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::saving(function (ItemBrand $model) {
            if ($model->is_default) {
                ItemBrand::where('id', '!=', $model->id)->update(['is_default' => 0]);
            }
        });
    }
}
