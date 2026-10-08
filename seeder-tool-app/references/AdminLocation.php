<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class AdminLocation extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = "admin_locations";

    protected $fillable = [
        'code',
        'name',
        'asset_tag_assign',
        'location_type',
        'active',
        'company_id',
        'store_branch_id',
        'admin_warehouse_id',
    ];
}
