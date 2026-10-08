<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorAddress extends Model
{
    use HasFactory, Caching;

    protected $table = "vendor_address";
    protected $fillable = [
        'customer_id',
        'country_code',
        'region_code',
        'province_code',
        'city_code',
        'address',
        'contact_person',
        'contact_number',
        'Active',
    ];

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'vendor_contact_person',
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
