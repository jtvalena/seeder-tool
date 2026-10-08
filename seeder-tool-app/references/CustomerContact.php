<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CustomerContact extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'customer_id',
        'email',
        'mobile_1',
        'mobile_2',
        'tel_1',
        'tel_2',
        'tel_3',
        'fax',
        'contact_type',
    ];
}
