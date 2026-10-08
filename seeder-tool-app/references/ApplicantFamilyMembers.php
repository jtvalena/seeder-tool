<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantFamilyMembers extends Model
{
    use HasFactory;
    protected $table = 'applicant_family_members';
    protected $connection = 'mysql_cpanel_cloud';

    protected $fillable = [
        'applicant_id',
        'name',
        'address',
        'relationship',
        'occupation',
    ];
}
