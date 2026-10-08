<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ClaimsPlatform extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'remarks',
        'active',
        'img',
    ];

    public function claims_shops()
    {
        return $this->hasMany(ClaimsShop::class);
    }

    public function claims_templates()
    {
        return $this->hasMany(ClaimsTemplate::class);
    }
}
