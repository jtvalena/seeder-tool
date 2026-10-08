<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehousePrepPrepareDateHistory extends Model
{
    use SoftDeletes;

    protected $table = 'warehouse_prep_prepare_date_history';

    protected $fillable = [
        'wp_list_id',
        'prepare_date',
        'user_id',
    ];
}
