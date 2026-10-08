<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CfTransmittalDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'cf_transmittal_details';

    protected $fillable = [
        'cf_transmittal_id',
        'module_id',
        'relationship_id',
        'release_date_time',
        'release_remarks'
    ];

    public function cf_transmittals()
    {
        return $this->belongsTo(CfTransmittal::class, 'id');
    }
}
