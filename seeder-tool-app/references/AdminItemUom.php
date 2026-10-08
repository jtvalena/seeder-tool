<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AdminItemUom extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory,SoftDeletes,Caching;

    protected $table = 'admin_item_uoms';

    protected $fillable = [
        'code',
        'description',
        'name',
    ];

    public static function boot()
    {
        parent::boot();
        static::created((function ($model) {
            $model->forgetByPattern('get_prf_admin_items:*');
        }));
        static::updated((function ($model) {
            $model->forgetByPattern('get_prf_admin_items:*');
        }));
        static::deleted((function ($model) {
            $model->forgetByPattern('get_prf_admin_items:*');
        }));
    }
}
