<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ManpowerRequest extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'company_id',
        'requestor_id',
        'job_title_id',
        'department_id',
        'request_reason_type_id',
        'education_type_id',
        'experience_type_id',
        'special_skill_ids',
        'special_fitness_ids',
        'no_of_personnel',
        'endorsed_by',
        'approved_by',
        'isApproved',
        'tenure',
        'date_required',
        'temporary_period_from',
        'temporary_period_to',
        'job_summary',
        'remarks',
        'date_submitted',
        'approval_date'
    ];
}
