<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CardNetwork extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'code',
        'name'
    ];
    public function card_network_prefixes()
    {
        return $this->hasMany(CardNetworkPrefix::class);
    }
}
