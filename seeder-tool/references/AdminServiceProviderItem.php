<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminServiceProviderItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'admin_service_provider_id',
        'service_number',
        'account_number',
        'account_name',
        'location',
        'location_id',
        'company_id',
        'store_branch_id',
        'package',
        'service_address',
        'customer_type',
        'electric_meter_number',
        'meter_reading_day',
        'company_expense_id',
        'expected_soa_date'
    ];
}
