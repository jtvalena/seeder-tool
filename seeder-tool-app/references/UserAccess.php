<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserAccess extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'user_accesses';

    protected $fillable = [
        'user_id',
        'department_id',
    ];

    public function useraccess(){
        return $this->belongsToMany(SideMenuBar::class);
    }
}
