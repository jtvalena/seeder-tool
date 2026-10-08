<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Items\Item;

class ItemSubstitution extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public $timestamps = false;
    protected $table = 'item_substitutions';

    protected $fillable = [
        'item_id',
        'new_item_id',
        'ranking'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function new_item()
    {
        return $this->belongsTo(Item::class, 'new_item_id');
    }
}
