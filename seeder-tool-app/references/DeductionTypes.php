<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DeductionTypes extends Model implements Auditable
{
    use HasFactory, Caching;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'code',
        'name',
        'with_amortization',
        'government',
        'require_attachment',
    ];

    protected $appends = [
        'isFixed'
    ];

    public function deduction_type_rates(){
        return $this->hasMany(DeductionTypeRates::class, 'deduction_type_id')->orderBy('year', 'asc');
    }

    public function getIsFixedAttribute(){
        return DeductionTypeRates::where('fixed', 1)->where('deduction_type_id', $this->id)->exists();
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'deduction_type_selection',
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
