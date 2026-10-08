<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model implements Auditable
{
    use HasFactory, Caching;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'parent_company_id',
        'company_classification_id',
        'code',
        'common_name',
        'registered_name',
        'registered_address',
        'billing_address',
        'shipping_address',
        'landline',
        'mobile',
        'fax',
        'primary_signatory',
        'secondary_signatory',
        'active',
        'system_id',
    ];

    public function storeBranches()
    {
        return $this->hasMany(StoreBranch::class, 'company_id');
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'company_selection',
            'companies_dropdown',
            'pv_companies'
        ];
        static::creating(function ($model) use($cache_names) {
            $model->forget($cache_names);
        });

        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));

        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }

    public function company_logos(){
        return $this->hasOne(CompanyLogo::class, 'company_id', 'id');
    }

}
