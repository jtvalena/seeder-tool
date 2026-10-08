<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationStatus extends Model
{
    use HasFactory, Caching;
    protected $table = 'application_statuses';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->forget('application_status_dropdown');
        });

        static::updated((function ($model) {
            $model->forget('application_status_dropdown');
        }));

        static::deleted((function ($model) {
            $model->forget('application_status_dropdown');
        }));
    }
}
