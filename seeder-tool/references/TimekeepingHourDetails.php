<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TimekeepingHourDetails extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'timekeeping_record_detail_id',
        'hour_type_id',
        'hours_worked',
        'holiday_pay_hours',
        'od',
        'odot',
        'odns',
        'odnsot',
        'snwd',
        'snwns',
        'snwot',
        'snwnsot',
        'rd',
        'rdns',
        'rdot',
        'rdnsot',
        'rh',
        'rhns',
        'rhot',
        'rhnsot',
        'rhrd',
        'rhrdns',
        'rhrdot',
        'rhrdnsot',
        'snwrd',
        'snwrdns',
        'snwrdot',
        'snwrdnsot',
    ];

    protected $appends = [
        'hour_type_name'
    ];

    public function hour_type(){
        return $this->belongsTo(HourType::class);
    }

    public function getHourTypeNameAttribute(){
        return $this->hour_type ? $this->hour_type->name : 'N/A';
    }
}
