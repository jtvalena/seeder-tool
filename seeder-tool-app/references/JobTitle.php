<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class JobTitle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Caching;

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

    protected $appends = [
        'subordinates'
    ];

    public function getSubordinatesAttribute(){
        $subordinates = JobTitle::getQuery()
            ->where('superior_id', $this->id)
            ->get();

        if(!empty($subordinates)){
            return $subordinates;
        }
        return [];
    }

    public static function boot()
    {
        parent::boot();

        static::created((function ($model) {
            $model->forget('job_titles_dropdown');
        }));
        static::updated((function ($model) {
            $model->forget('job_titles_dropdown');
        }));
        static::deleted((function ($model) {
            $model->forget('job_titles_dropdown');
        }));
    }
}
