<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantEmergencyContacts extends Model
{
    use HasFactory;
    protected $table = 'applicant_emergency_contacts';
    protected $connection = 'mysql_cpanel_cloud';

    protected $fillable = [
        'applicant_id',
        'name',
        'address',
        'relationship',
        'contact_num',
    ];
}
