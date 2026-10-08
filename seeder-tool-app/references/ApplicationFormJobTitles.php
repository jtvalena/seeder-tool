<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationFormJobTitles extends Model
{
    use HasFactory, Caching;
    protected $fillable = [
        'department_id',
        'company_id',
        'superior_id',
        'department_id',
        'job_level_id',
        'rank',
        'code',
        'name',
        'active',
        'job_description'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->forget('application_form_job_titles');
        });

        static::updated((function ($model) {
            $model->forget('application_form_job_titles');
        }));

        static::deleted((function ($model) {
            $model->forget('application_form_job_titles');
        }));
    }

}
