<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ScRfStatuses extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    // protected $fillable = [
    //     'text' => 'DIS', 'description' => 'Disposal'
    // ];
}
