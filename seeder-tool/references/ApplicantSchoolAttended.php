<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantSchoolAttended extends Model
{
    use HasFactory;
    protected $table = 'applicant_school_attendeds';
    protected $connection = 'mysql_cpanel_cloud';

    protected $fillable = [
        'applicant_id',
        'school_attended',
        'field_course',
        'year_from',
        'year_to',
        'degree_earned',
    ];
}
