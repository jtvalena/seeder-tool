<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ClaimsTemplate extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'remarks',
        'active',
    ];

    public function details()
    {
        return $this->hasMany(ClaimsTemplateItem::class);
    }

    public function discounts()
    {
        return $this->hasMany(ClaimsTemplateDiscount::class);
    }
}
