<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BookletItem extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'booklet_id',
        'booklet_cheque_num',
        'status',
        'cancelled_by',
        'cancelled_date',
        'used_by'
    ];
}
