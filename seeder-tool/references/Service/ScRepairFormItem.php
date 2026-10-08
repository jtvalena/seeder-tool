<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Service\ScRepairForm;
use App\Models\Items\Item;
use App\Models\Emoji;
use App\Traits\Caching;

class ScRepairFormItem extends Model implements Auditable
{
    use HasFactory, Caching;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'item_id',
    ];

    protected $appends = [
        'model',
        'product_name',
        'category',
    ];

    public function sc_repair_form()
    {
        return $this->belongsTo(ScRepairForm::class);
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function emoji()
    {
        return $this->hasMany(Emoji::class, 'id', 'emoji_id');
    }

    // ── Computed accessors (preserve API response shape) ─────────────────────

    public function getModelAttribute(): ?string
    {
        return $this->relationLoaded('item') ? ($this->item?->model) : null;
    }

    public function getProductNameAttribute(): ?string
    {
        return $this->relationLoaded('item') ? ($this->item?->name) : null;
    }

    public function getCategoryAttribute(): ?string
    {
        if (!$this->relationLoaded('item')) {
            return null;
        }
        $item = $this->item;
        return $item && $item->relationLoaded('itemCategory')
            ? ($item->itemCategory?->name)
            : null;
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'back_job_selection',
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
