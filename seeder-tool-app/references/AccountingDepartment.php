<?php

namespace App\Models;
use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;
class AccountingDepartment extends Model implements Auditable
{
    use AuditingAuditable, HasFactory,SoftDeletes,Caching;

    protected $fillable = ['code','name','user_department_id','active'];


    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'department_dropdown_accounting',
        ];
        static::created((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }
}
