<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantPreviousEmployment extends Model
{
    use HasFactory;
    protected $table = 'applicant_previous_employments';
    protected $connection = 'mysql_cpanel_cloud';

    protected $fillable = [
        'applicant_id',
        'prev_employer_name',
        'year_month_from',
        'year_month_to',
        'monthly_salary',
        'reason_for_leaving',
        'previous_employment_position',
    ];
}
