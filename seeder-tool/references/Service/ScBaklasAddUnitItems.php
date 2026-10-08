<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScBaklasAddUnitItems extends Model
{
    use HasFactory;
    use SoftDeletes;
 
    protected $table = 'baklas_add_unit_items';
    protected $fillable = [
        'baklas_add_unit_id',
        'item_id',
        'quantity'
    ];
}
