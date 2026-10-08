<?php

namespace App\Models;

use App\Models\Operations\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ContainerType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'item_category_ids',
        'initial',
        'parent_id',
    ];

    public function containers()
    {
        return $this->hasMany(Container::class);
    }
}
