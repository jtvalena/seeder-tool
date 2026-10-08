<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminDeliveryAddress extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "admin_delivery_addresses";

    protected $fillable = [
        'code',
        'name',
        'asset_tag_assign',
        'location_type',
        'active',
        'address',
        'contact_number',
        'type',
    ];
}
