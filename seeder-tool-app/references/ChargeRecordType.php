<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ChargeRecordType extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'vendor_id',
        'company_id',
        'store_branches_id',
        'terms',
        'remarks',
        'active',
    ];

    public function charge_record_type_items()
    {
        return $this->hasMany(ChargeRecordTypeItem::class, 'charge_record_type_id');
    }

    public function charge_record_type_departments()
    {
        return $this->hasMany(ChargeRecordTypeDepartment::class, 'charge_record_type_id');
    }
}
