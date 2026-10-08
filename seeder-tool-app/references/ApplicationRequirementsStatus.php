<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationRequirementsStatus extends Model
{
    protected $connection = 'mysql_cpanel_cloud';
    protected $table = 'application_requirements_statuses';
    protected $fillable = [
        'requirement_id',
        'applicant_id',
        'approved'
    ];

    use HasFactory;
}
