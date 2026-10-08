<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form2307SignatureSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'form_2307_signature_settings';

    protected $fillable = [
        'signature_employee_id',
        'signature_name',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'signature_employee_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];
}

