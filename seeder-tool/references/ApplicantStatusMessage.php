<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantStatusMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'application_status_id',
        'message'
    ];
}
