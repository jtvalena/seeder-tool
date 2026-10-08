<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class System extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = "systems";

    protected $fillable = [
        'code',
        'name',
        'title_color',
        'theme_color',
        'default',
        'current',
        'brand_name',
        'connection_name',
        'api_host',
    ];
}
