<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeFamilyMembers extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'name',
        'address',
        'relationship',
        'occupation',
    ];

    protected $appends = [
        'relationship'
    ];

    public function getRelationshipAttribute()
    {
        return ucwords(strtoupper(str_replace('_', ' ', $this->attributes['relationship'])));
    }
}
