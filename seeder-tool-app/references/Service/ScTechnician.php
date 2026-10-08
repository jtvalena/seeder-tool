<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Service\ScRepairForm;
use App\Traits\Caching;

class ScTechnician extends Model implements Auditable
{
    use HasFactory, Caching;
    use \OwenIt\Auditing\Auditable;

    public function sc_repair_forms() {
        return $this->hasMany(ScRepairForm::class);
    }

    public function sc_technician_images() {
        return $this->hasMany(ScTechnicianImage::class);
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'sc_technicians',
            'technician_dropdown',
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
