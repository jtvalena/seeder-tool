<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProvincialHoliday extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;


    protected $fillable = [
        'holiday_id',
        'store_branch_id'
    ];
}
