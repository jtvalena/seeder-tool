<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicantRequirementsUploads extends Model
{
    use SoftDeletes;
    protected $connection = 'mysql_cpanel_cloud';
    protected $table = 'applicant_requirements_uploads';
    use HasFactory;
}
