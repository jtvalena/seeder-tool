<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HRUserCompanyItems extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'hr_user_company_items';

    protected $fillable = [
        'hr_user_id',
        'company_id'
    ];
}
