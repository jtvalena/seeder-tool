<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class IDComponentSettings extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'id_component_settings';

    protected $fillable = [
        'template_id',
        'component_type_id',
        'x',
        'y',
        'placement_type',
    ];
}
