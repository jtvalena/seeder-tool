<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoContainerMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_id',
        'container_id',
        'item_id',
        'quantity'
    ];

    public function item(){
        return $this->belongsTo(Item::class);
    }
}
