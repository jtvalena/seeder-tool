<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Department extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'departments';

    protected $fillable = [
        'code',
        'name',
        'active',
        'manager_ids',
        'admin_code',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function user_access(){
        return $this->hasMany(UserAccess::class,'department_id','id');
    }

    public function work_schedule_items(){
        return $this->hasMany(WorkScheduleItems::class, 'department_id', 'id');
    }

}
