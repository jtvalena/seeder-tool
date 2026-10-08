<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrUserJobLevelItems extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;


    protected $fillable = [
        'user_id',
        'job_level_id',
        'hr_user_id',
    ];
}
