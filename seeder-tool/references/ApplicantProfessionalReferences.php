<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantProfessionalReferences extends Model
{
    use HasFactory;
    protected $table = 'applicant_professional_references';
    protected $connection = 'mysql_cpanel_cloud';

    protected $fillable = [
        'applicant_id',
        'name',
        'address',
        'contact_num',
        'occupation',
    ];
}
