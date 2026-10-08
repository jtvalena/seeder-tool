<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ApplicationRequirements extends Model implements Auditable
{
    protected $connection = 'mysql_cpanel_cloud';

    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
