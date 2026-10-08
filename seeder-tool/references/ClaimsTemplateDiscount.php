<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ClaimsTemplateDiscount extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'claims_template_id',
        'display_as',
        'item_category_ids',
        'base_discount',
        'subsidy_discount',
    ];
}
