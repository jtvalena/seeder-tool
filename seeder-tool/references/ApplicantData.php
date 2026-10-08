<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApplicantAddress;
use App\Models\ApplicantFamilyMembers;
use App\Models\ApplicantEmergencyContacts;
use App\Models\ApplicantSchoolAttended;
use App\Models\ApplicantProfessionalReferences;
use App\Models\ApplicantPreviousEmployment;
use OwenIt\Auditing\Contracts\Auditable;


class ApplicantData extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $table = 'applicant_data';

    public function employee_addresses(){
        return $this->hasMany(ApplicantAddress::class,'applicant_id','id');
    }

    public function employee_family_members(){
        return $this->hasMany(ApplicantFamilyMembers::class,'applicant_id','id');
    }

    public function employee_emergency_contacts(){
        return $this->hasMany(ApplicantEmergencyContacts::class,'applicant_id','id');
    }

    public function employee_schools_attendeds(){
        return $this->hasMany(ApplicantSchoolAttended::class,'applicant_id','id');
    }

    public function employee_professional_references(){
        return $this->hasMany(ApplicantProfessionalReferences::class,'applicant_id','id');
    }

    public function employee_previous_employments(){
        return $this->hasMany(ApplicantPreviousEmployment::class,'applicant_id','id');
    }
}
