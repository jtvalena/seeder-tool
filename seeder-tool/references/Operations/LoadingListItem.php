<?php

namespace App\Models\Operations;

use App\Traits\MorphMap;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class LoadingListItem extends Model implements Auditable
{
    use HasFactory, SoftDeletes, MorphMap, \OwenIt\Auditing\Auditable;

    protected $appends = [];

    protected $fillable = [
        'loading_list_id',
        'item_id',
        'quantity',
    ];

    public function loading_list()
    {
        return $this->belongsTo(LoadingList::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
