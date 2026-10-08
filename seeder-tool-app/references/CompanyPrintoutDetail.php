<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyPrintoutDetail extends Model  implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
            'company_id',
            'module',
            'address',
            'landline',
            'mobile',
            'fax',
            'viber',
            'email',
        ];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
