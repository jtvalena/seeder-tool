<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class IDComponentType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'id_component_types';

}
