<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BioMetricApi extends Model
{
    use HasFactory, Caching;

    protected $fillable = ['protocol','host','active'];


    public function portAPI(){
        return $this->hasMany(BioMetricApiPorts::class,'host_id');
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'bio_metric_location',
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
