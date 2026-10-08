<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminAssetTurnoverItemFile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'path',
        'extension',
        'type',
        'upload_by',
        'admin_asset_turnover_item_id',
    ];
}
