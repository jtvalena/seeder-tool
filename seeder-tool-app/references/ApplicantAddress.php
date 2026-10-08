<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantAddress extends Model
{
    use HasFactory;
    protected $table = 'applicant_addresses';
    protected $connection = 'mysql_cpanel_cloud';

    protected $fillable = [
        'applicant_id',
        'country_code',
        'region_code',
        'province_code',
        'city_code',
        'address',
        'address_type',
        'updated_at',
        'created_at',
    ];
}
