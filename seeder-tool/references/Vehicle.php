<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Vehicle extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "vehicles";

    protected $fillable = [
        'code',
        'name',
        'length',
        'width',
        'height',
        'cbm',
        'brand',
        'type',
        'plate_number',
        'tpl',
        'conduction_sticker',
        'company_id',
        'owner_name',
        'address',
        'year_model',
        'color',
        'accounted_to',
        'admin_location_id',
        'certificate_of_registration_number',
        'date_of_certification_of_registration_number',
        'admin_purchase_order_id',
        'official_receipt_number',
        'latest_official_receipt_date',
        'engine_number',
        'serial_number',
        'mv_filer_number',
        'fuel',
        'remarks',
        'keys',
        'rfid_account_number',
        'rfid_easy_trip', 
        'rfid_auto_sweep',
        'unit_description',
        'expense',
        'purchase_amount',
        'purchase_date',
        'code_name',
        'admin_info_remarks'
    ];

    // public function stockTransferShipper(): HasMany
    // {
    //     return $this->hasMany(StockTransferShipper::class, 'vehicle_id');
    // }

    public function getNumberCodingDayAttribute()
    {
        $day = null;

        switch ($this->number_coding) {
            case 1:
            case 2:
                $day = 'Monday';
                break;
            case 3:
            case 4:
                $day = 'Tuesday';
                break;
            case 5:
            case 6:
                $day = 'Wednesday';
                break;
            case 7:
            case 8:
                $day = 'Thursday';
                break;
            case 9:
            case 0:
                $day = 'Friday';
                break;
            default:
                $day = 'unknown';
        }

        return $day;
    }
}
