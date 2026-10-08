<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BiotimeData extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        "emp_code",
        "punch_time",
        "punch_state",
        "verify_type",
        "work_code",
        "terminal_sn",
        "terminal_alias",
        "area_alias",
        "longitude",
        "latitude",
        "gps_location",
        "mobile",
        "source",
        "purpose",
        "crc",
        "is_attendance",
        "reserved",
        "upload_time",
        "sync_status",
        "sync_time",
        "is_mask",
        "temperature",
        "emp_id",
        "terminal_id",
        "late_synced",
    ];

    protected $casts = [
        'emp_code' => 'integer',
    ];
}
