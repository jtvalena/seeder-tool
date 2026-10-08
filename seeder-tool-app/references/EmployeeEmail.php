<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeEmail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'email_type_id',
        'email'
    ];

    protected $appends = [
        'type_name'
    ];

    public function getTypeNameAttribute()
    {
        $id = EmailType::getQuery()->where('id',$this->email_type_id)->value('name');
        return $id;
    }
}
